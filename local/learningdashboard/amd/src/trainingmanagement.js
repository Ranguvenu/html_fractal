/**
 * Add a create new group modal to the page.
 *
 * @module     local_courses/trainingmanagement
 * @class      trainingmanagement
 * @package    local_courses
 * @copyright  2018 Sreenivas
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later 
 */
define(['local_courses/jquery.dataTables', 'jquery', 'core/str', 'core/modal_factory', 'core/modal_events', 'core/fragment', 'core/ajax', 'core/yui', 'core/templates'],
    function (dataTable, $, Str, ModalFactory, ModalEvents, Fragment, Ajax, Y, Templates) {

        /**
        * Constructor
        *
        * @param {object} args
        *
        * Each call to init gets it's own instance of this class.
        */
        var trainingmanagement = function (args) {
            this.contextid = args.contextid ? args.contextid : 1;
            this.args = args;
            this.init(args);
            this.tunit = args.tunit;
        };

        /**
        * @var {Modal} modal
        * @private
        */
        trainingmanagement.prototype.modal = null;

        /**
        * @var {int} contextid
        * @private
        */
        trainingmanagement.prototype.contextid = -1;

        /**
        * Initialise the class.
        *
        * @param {String} selector used to find triggers for the new group modal.
        * @private
        * @return {Promise}
        */
        trainingmanagement.prototype.init = function (args) {
            // Fetch the title string.
            var self = this;

            var head = {
                key: 'courses', component: 'block_training_management',
            };
            customstrings = Str.get_strings([head,
                {
                    key: 'squads', component: 'block_training_management'
                },
                {
                    key: 'users', component: 'block_training_management'
                },
                {
                    key: 'instructors', component: 'block_training_management'
                },
                {
                    key: 'close',
                    component: 'block_training_management'
                },
                {
                    key: 'reviews',
                    component: 'block_training_management'
                },
                {
                    key: 'gradeshistory',
                    component: 'local_instructor_rating'
                },
                ]);



            return customstrings.then(function (strings) {
                // Create the modal.
                var title = '';
                if (this.args.callback == 'coursespopup') {
                    title = strings[0];
                } else if (this.args.callback == 'squads') {
                    title = strings[1];
                } else if (this.args.callback == 'users') {
                    title = strings[2];
                } else if (this.args.callback == 'instructors') {
                    title = strings[3];
                }else if (this.args.callback == 'reviews') {
                    title = strings[5];
                }else if (this.args.callback == 'gradeshistory') {
                    title = strings[6];
                }
                return ModalFactory.create({
                    type: ModalFactory.types.CANCEL,
                    title: title,
                    body: this.getBody(),
                });
            }.bind(this)).then(function (modal) {
                // Keep a reference to the modal.
                this.modal = modal;
                // Forms are big, we want a big modal.
                self.modal.setLarge();

                // We want to reset the form every time it is opened.
                self.modal.getRoot().on(ModalEvents.hidden, function () {
                    self.modal.setBody('');
                    self.modal.hide();
                    self.modal.destroy();
                }.bind(this));

                // We want to reset the form every time it is opened.
                self.modal.getRoot().on(ModalEvents.cancel, function () {
                    self.modal.setBody('');
                    self.modal.hide();
                    self.modal.destroy();
                }.bind(this));
                this.modal.getRoot().on(ModalEvents.bodyRendered, function () {
                    this.dataTableshow(args.tunit);
                }.bind(this));
                self.modal.show();
                return this.modal;
            }.bind(this));
        };
        trainingmanagement.prototype.dataTableshow = function (tunit) {
            // console.log(tunit);
            $.fn.dataTable.ext.errMode = 'none';
            $('.managementpopuptable_details').dataTable({
                'bPaginate': true,
                'bFilter': true,
                'bLengthChange': true,
                'lengthMenu': [
                    [5, 10, 25, 50, 100, -1],
                    [5, 10, 25, 50, 100, 'All']
                ],
                'language': {
                    'emptyTable': 'No Records Found',
                    'paginate': {
                        'previous': '<',
                        'next': '>'
                    }
                },

                'bProcessing': true,
            });
        };
        /**
        * @method getBody
        * @private
        * @return {Promise}
        */
        trainingmanagement.prototype.getBody = function (args) {
            // Get the content of the modal.
            console.log(this.args);
            return Fragment.loadFragment(this.args.component, this.args.callback, 1, this.args);
        };
        /**
        * @method getFooter
        * @private
        * @return {Promise}
        */
        trainingmanagement.prototype.getFooter = function (customstrings) {
            var footer = '';
            footer = '<button type="button" class="btn btn-secondary" data-action="cancel">' + customstrings[0] + '</button>';
            return footer;
            // }.bind(this));
        };
        /**
        * @method getFooter
        * @private
        * @return {Promise}
        */
        trainingmanagement.prototype.getcontentFooter = function () {
            return Str.get_strings([{
                key: 'cancel'
            }]).then(function (s) {
                $footer = '<button type="button" class="btn btn-secondary" data-action="cancel">' + s[1] + '</button>';
                return $footer;
            }.bind(this));
        };

        return /** @alias module:core_group/trainingmanagement */ {
            // Public variables and functions.
            /**
             * Attach event listeners to initialise this module.
             *
             * @method init
             * @param {string} selector The CSS selector used to find nodes that will trigger this module.
             * @param {int} contextid The contextid for the course.
             * @return {Promise}
             */
            init: function (args) {
                // this.Datatable();
                return new trainingmanagement(args);
            },
            Datatable: function () {

            },
            courses: function (args) {
                $('#training_management_courses').dataTable({
                    'bPaginate': true,
                    'bFilter': true,
                    'bLengthChange': false,
                    "pageLength" : 5,
                    // 'lengthMenu': [
                    //     [5, 10, 25, 50, 100, -1],
                    //     [5, 10, 25, 50, 100, 'All']
                    // ],
                    'language': {
                        'emptyTable': 'No Records Found',
                        'paginate': {
                            'previous': '<',
                            'next': '>'
                        }
                    },

                    'bProcessing': true,
                    'ordering': false,
                });

                $.fn.dataTable.ext.errMode = 'none';
            },
            squads: function (args) {
                $('#training_management_squads').dataTable({
                    'bPaginate': true,
                    'bFilter': true,
                    'bLengthChange': false,
                    "pageLength" : 5,
                    // 'lengthMenu': [
                    //     [5, 10, 25, 50, 100, -1],
                    //     [5, 10, 25, 50, 100, 'All']
                    // ],
                    'language': {
                        'emptyTable': 'No Records Found',
                        'paginate': {
                            'previous': '<',
                            'next': '>'
                        }
                    },

                    'bProcessing': true,
                    'ordering': false,
                });
                $.fn.dataTable.ext.errMode = 'none';
            },
            load: function () { }
        };
    });