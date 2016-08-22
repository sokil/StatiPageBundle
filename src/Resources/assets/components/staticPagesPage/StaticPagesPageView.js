var StaticPagesPageView = Backbone.View.extend({
    render: function() {
        // render page
        this.$el.html(app.render('StaticPagesPage'));

        // render list
        this.listView = new StaticPageListView({
            el: this.$el.find('.content'),
            collection: new StaticPageCollection()
        });
        this.listView.render();
    }
});