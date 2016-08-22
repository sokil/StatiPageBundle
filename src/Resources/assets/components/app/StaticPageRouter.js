var StaticPageRouter = Marionette.AppRouter.extend({

    routes: {
        "pages": "pagesAction",
        "pages/:id/edit": "editPageAction",
        "pages/new": "editPageAction"
    },

    /**
     * Static pages list
     */
    pagesAction: function() {
        app.rootView.content.show(new StaticPagesPageView());
    },

    // get model
    editPageAction: function(id) {
        // create model
        var collection = new StaticPageCollection();
        var model = collection.add({id: id});

        // render popup
        app.rootView.content.show(new StaticPageEditorView({
            model: model
        }));
    }
});