var StaticPageCollection = Backbone.Collection.extend({
    model: StaticPage,
    url: '/pages',
    parse: function(response) {
        return response.pages;
    }
});