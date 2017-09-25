(function() {
    tinymce.create('tinymce.plugins.collection', {
        init : function(ed, url) {
            ed.addButton('collection', {
                title : 'Add a Collection',
                image : url+'/shortcodes/collection.png',
                onclick : function() {
					ed.selection.setContent('[collection]');
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('collection', tinymce.plugins.collection);
})();