var StaticPageEditorView = Marionette.ItemView.extend({

    tinymceEditors: {},

    events: {
        'click input[type="submit"]': 'saveClickListener'
    },

    render: function() {
        if (this.model.isNew()) {
            this.renderAsync();
        } else {
            this.listenTo(this.model, 'sync', this.renderAsync);
            this.model.fetch();
        }
    },

    onDestroy: function() {
        // de-init tinymce editors
        _.map(this.tinymceEditors, function(editor) {
            editor.remove();
        });
    },

    renderAsync: function() {
        var self = this;

        this.$el.html(app.render('StaticPageEditor', {
            page: this.model
        }));

        // init tinymce
        require(['staticpage_tinymce'], function(tinymce) {
            // init
            tinymce
                .init({
                    selector: '#pageEditor textarea',
                    height: '400px',
                    plugins: "table link image code fullscreen textcolor",
                    menubar: false,
                    statusbar: false,
                    toolbar: [
                        [
                            "undo redo",
                            "styleselect bold italic",
                            "forecolor backcolor",
                            "alignleft aligncenter alignright",
                            "link unlink",
                            "table",
                            "image",
                            "bullist numlist",
                            "outdent indent",
                            "code fullscreen"
                        ].join(" | ")
                    ],
                    setup: function(editor) {
                        self.tinymceEditors[editor.id] = editor;
                    },
                    resize: true
                });
        });
    },

    saveClickListener: function() {
        var self = this,
            $form = this.$el.find('form');

        // remove status
        this.$el.find('.status').removeClass('alert-warning').empty();

        // prepate data
        var data = UrlMutator.unserializeQuery(decodeURIComponent($form.serialize().replace(/\+/g,  " ")));
        var editor, lang;
        for (var i = 0; i < tinymce.editors.length; i++) {
            editor = tinymce.editors[i];
            lang = editor.id.match(/body\[(.*)\]/)[1];
            data.body[lang] = editor.getContent();
        }

        // save model
        var xhr = this.model.save(data);
        xhr.success(function(response) {
            if (response.error === 0) {
                // redirect to page
                location.href = self.model.get('slug');
            }

            self.$el.find('.status').addClass('alert-warning').text(response.message);
        });
        
        return false;
    }
});