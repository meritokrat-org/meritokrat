var docsController = new function()
{  
    this.editAction = function ()
    {
        var form = new Form(
            'edit_form',
            {
                    validators:
                    {
                            alias: [validatorRequired],
                            title: [validatorRequired]
                    },
                    success: function( response )
                    {
                            document.location = '/docs';
                    },
                    wait_panel: 'wait_panel'
            });

    };
}    