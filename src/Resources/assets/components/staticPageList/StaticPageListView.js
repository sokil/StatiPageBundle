var StaticPageListView = Backbone.View.extend({
    events: {
        'click .delete': 'deleteButtonClickListener'
    },

    render: function() {
        this.listenTo(this.collection, 'sync', this.renderAsync);
        this.collection
            .fetch()
            .fail(function(xhr) {
                if (xhr.status === 403) {
                    app.router.navigate('', {trigger: true});
                }
            });
    },

    renderAsync: function() {
        if (this.collection.models.length === 0) {
            this.$el.html(app.render('StaticPageEmptyList'));
            return;
        }

        this.$el.html(app.render('StaticPageList', {
            pages: this.collection.models
        }));
    },

    deleteButtonClickListener: function(e) {
        var self = this,
            $btn = $(e.currentTarget);

        // get model
        var collection = new StaticPageCollection();
        var model = collection.add({id: $btn.data('id')});

        // delete
        model.on('sync', function() {
            $btn.closest('tr').remove();
        });
        model.destroy();
    }
});