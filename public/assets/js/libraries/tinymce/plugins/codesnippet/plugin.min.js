tinymce.PluginManager.add('codesnippet', function(editor, url) {
  const openDialog = () => editor.windowManager.open({
    title: 'Code Snippet',
    body: {
      type: 'panel',
      items: [
        {type: 'selectbox', name: 'lang', label: 'Sélectionnez le langage', items: editor.getParam('codesnippet_languages')},
        {type: 'textarea', name: 'snippet', label: 'Coller votre code ci-dessous', maximized: true}
      ]
    },
    buttons: [
      {type: 'cancel', text: 'Fermer'},
      {type: 'submit', text: 'Enregistrer', buttonType: 'primary'}
    ],
    onSubmit: (api) => {
      const data = api.getData();
      let html = '<p><pre class="language-' + data.lang + '" tabindex="0"><code class="language-css">' + data.snippet + '</code></pre></p>';
      editor.insertContent(html);
      api.close();
    }
  });
  editor.ui.registry.addButton('codesnippet', {
    text: '</>',
    onAction: () => {
      openDialog();
      let area = document.getElementsByClassName('tox-textarea');
      area[0].setAttribute('style', 'height:400px');
    }
  });
  return {
    getMetadata: () => ({
      name: 'codesnippet',
      url: 'http://www.comnstay.fr/blog/de-beaux-snippet-de-code-dans-tinymce'
    })
  };
});
  