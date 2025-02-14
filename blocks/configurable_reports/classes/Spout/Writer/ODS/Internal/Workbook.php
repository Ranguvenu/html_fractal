<?php

namespace block_configurable_reports\Spout\Writer\ODS\Internal;

use block_configurable_reports\Spout\Writer\Common\Internal\AbstractWorkbook;
use block_configurable_reports\Spout\Writer\ODS\Helper\FileSystemHelper;
use block_configurable_reports\Spout\Writer\ODS\Helper\StyleHelper;
use block_configurable_reports\Spout\Writer\Common\Sheet;

/**
 * Class Workbook
 * Represents a workbook within a ODS file.
 * It provides the functions to work with worksheets.
 *
 * @package block_configurable_reports\Spout\Writer\ODS\Internal
 */
class Workbook extends AbstractWorkbook
{
    /**
     * Maximum number of rows a ODS sheet can contain
     * @see https://ask.libreoffice.org/en/question/8631/upper-limit-to-number-of-rows-in-calc/
     */
    protected static $maxRowsPerWorksheet = 1048576;

    /** @var \block_configurable_reports\Spout\Writer\ODS\Helper\FileSystemHelper Helper to perform file system operations */
    protected $fileSystemHelper;

    /** @var \block_configurable_reports\Spout\Writer\ODS\Helper\StyleHelper Helper to apply styles */
    protected $styleHelper;

    /**
     * @param string $tempFolder
     * @param bool $shouldCreateNewSheetsAutomatically
     * @param \block_configurable_reports\Spout\Writer\Style\Style $defaultRowStyle
     * @throws \block_configurable_reports\Spout\Common\Exception\IOException If unable to create at least one of the base folders
     */
    public function __construct($tempFolder, $shouldCreateNewSheetsAutomatically, $defaultRowStyle)
    {
        parent::__construct($shouldCreateNewSheetsAutomatically, $defaultRowStyle);

        $this->fileSystemHelper = new FileSystemHelper($tempFolder);
        $this->fileSystemHelper->createBaseFilesAndFolders();

        $this->styleHelper = new StyleHelper($defaultRowStyle);
    }

    /**
     * @return \block_configurable_reports\Spout\Writer\ODS\Helper\StyleHelper Helper to apply styles to ODS files
     */
    protected function getStyleHelper()
    {
        return $this->styleHelper;
    }

    /**
     * @return int Maximum number of rows/columns a sheet can contain
     */
    protected function getMaxRowsPerWorksheet()
    {
        return self::$maxRowsPerWorksheet;
    }

    /**
     * Creates a new sheet in the workbook. The current sheet remains unchanged.
     *
     * @return Worksheet The created sheet
     * @throws \block_configurable_reports\Spout\Common\Exception\IOException If unable to open the sheet for writing
     */
    public function addNewSheet()
    {
        $newSheetIndex = count($this->worksheets);
        $sheet = new Sheet($newSheetIndex);

        $sheetsContentTempFolder = $this->fileSystemHelper->getSheetsContentTempFolder();
        $worksheet = new Worksheet($sheet, $sheetsContentTempFolder);
        $this->worksheets[] = $worksheet;

        return $worksheet;
    }

    /**
     * Closes the workbook and all its associated sheets.
     * All the necessary files are written to disk and zipped together to create the ODS file.
     * All the temporary files are then deleted.
     *
     * @param resource $finalFilePointer Pointer to the ODS that will be created
     * @return void
     */
    public function close($finalFilePointer)
    {
        /** @var Worksheet[] $worksheets */
        $worksheets = $this->worksheets;
        $numWorksheets = count($worksheets);

        foreach ($worksheets as $worksheet) {
            $worksheet->close();
        }

        // Finish creating all the necessary files before zipping everything together
        $this->fileSystemHelper
            ->createContentFile($worksheets, $this->styleHelper)
            ->deleteWorksheetTempFolder()
            ->createStylesFile($this->styleHelper, $numWorksheets)
            ->zipRootFolderAndCopyToStream($finalFilePointer);

        $this->cleanupTempFolder();
    }

    /**
     * Deletes the root folder created in the temp folder and all its contents.
     *
     * @return void
     */
    protected function cleanupTempFolder()
    {
        $xlsxRootFolder = $this->fileSystemHelper->getRootFolder();
        $this->fileSystemHelper->deleteFolderRecursively($xlsxRootFolder);
    }
}
