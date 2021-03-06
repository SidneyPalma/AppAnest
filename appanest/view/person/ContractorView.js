//@charset ISO-8859-1
Ext.define( 'AppAnest.view.person.ContractorView', {
    extend: 'Ext.container.Container',

    xtype: 'contractorview',

    requires: [
        'Ext.tab.*',
        'Smart.form.field.ComboEnum',
        'Smart.address.TextAddress',
        'Smart.address.SearchAddress',
        'AppAnest.person.PersonPhone',
        'AppAnest.person.PersonAddress'
    ],

    controller: 'contractor',

    padding: 10,

    layout: {
        type: 'fit'
    },

    initComponent: function () {
        var me = this;
        me.buildItems();
        me.callParent();
    },

    buildItems: function () {
        var me = this;

        me.items = [
            {
                frame: true,
                xtype: 'panel',

                bodyStyle: 'padding: 30px 10px 10px 10px;',

                iconCls: 'icon-hospital',

                layout: {
                    type: 'hbox',
                    align: 'stretch'
                },

                header: {
                    title: 'Cadastro do Contratante',
                    items: [
                        {
                            xtype: 'button',
                            glyph: 0xeb4e,
                            handler: 'onHistoryBack'
                        }
                    ]
                },

                items: [
                    {
                        flex: 1,
                        xtype: 'container',
                        cls: 'smart-theme wallpaper'
                    }, {
                        width: 600,
                        xtype: 'form',
                        layout: 'border',
                        items: [
                            {
                                region: 'center',
                                xtype: 'panel',
                                layout: 'border',
                                items: [
                                    {
                                        height: 250,
                                        region: 'north',
                                        xtype: 'panel',
                                        layout: 'border',
                                        items: [
                                            {
                                                width: 150,
                                                layout: 'border',
                                                region: 'west',
                                                xtype: 'panel',
                                                items: [
                                                    {
                                                        height: 200,
                                                        region: 'north',
                                                        xtype: 'portrait',
                                                        tableName: 'person'
                                                    }, {
                                                        region: 'center',
                                                        xtype: 'panel'
                                                    }
                                                ]
                                            }, {
                                                width: 20,
                                                region: 'west',
                                                xtype: 'panel'
                                            }, {
                                                layout: 'anchor',
                                                region: 'center',
                                                xtype: 'panel',
                                                defaultType: 'textfield',
                                                defaults: {
                                                    anchor: '100%'
                                                },
                                                items: [
                                                    {
                                                        xtype: 'hiddenfield',
                                                        name: 'id'
                                                    }, {
                                                        xtype: 'hiddenfield',
                                                        name: 'typeperson',
                                                        value: 'C'
                                                    }, {
                                                        xtype: 'container',
                                                        layout: 'hbox',
                                                        defaultType: 'textfield',
                                                        items: [
                                                            {
                                                                flex: 5,
                                                                fieldLabel: 'Nome fantasia',
                                                                name: 'shortname'
                                                            }, {
                                                                xtype: 'splitter'
                                                            }, {
                                                                flex: 2,
                                                                maskRe: /[0-9\/]/,
                                                                fieldLabel: 'CNES',
                                                                name: 'cnesnumber'
                                                            }
                                                        ]
                                                    }, {
                                                        fieldLabel: 'Razão social',
                                                        name: 'name'
                                                    }, {
                                                        vtype: 'email',
                                                        name: 'mainmail',
                                                        fieldLabel: 'E-mail principal'
                                                    }, {
                                                        name: 'maincontact',
                                                        fieldLabel: 'Contato principal'
                                                    }, {
                                                        name: 'isactive',
                                                        xtype: 'checkboxfield',
                                                        boxLabel: 'Ativo'
                                                    }
                                                ]
                                            }
                                        ]
                                    }, {
                                        region: 'center',
                                        xtype: 'tabpanel',
                                        deferredRender: false,
                                        ui: 'navigation',
                                        tabBar: {
                                            layout: {
                                                pack: 'center'
                                            }
                                        },
                                        items: [
                                            {
                                                overflowY: 'auto',
                                                glyph: 0xe9eb,
                                                title: 'Complemento',
                                                layout: 'anchor',
                                                bodyStyle: 'padding-top: 10px',
                                                items: [
                                                    {
                                                        xtype: 'label',
                                                        text: 'Inscrições',
                                                        style: {
                                                            color: 'blue;',
                                                            fontSize: '14px;'
                                                        }
                                                    }, {
                                                        xtype: 'container',
                                                        layout: 'hbox',
                                                        margin: '0 0 10 0',
                                                        defaultType: 'textfield',
                                                        items: [
                                                            {
                                                                flex: 1,
                                                                fieldLabel: 'CNPJ',
                                                                plugins: 'textmask',
                                                                money: false,
                                                                name: 'cnpjnumber',
                                                                mask: '99.999.999/9999-99'
                                                            }, {
                                                                xtype: 'splitter'
                                                            }, {
                                                                flex: 1,
                                                                fieldLabel: 'Municipal',
                                                                name: 'countyregistration'
                                                            }, {
                                                                flex: 2,
                                                                xtype: 'container'
                                                            }
                                                        ]
                                                    }
                                                ]
                                            }, {
                                                xtype: 'personaddress'
                                            }, {
                                                xtype: 'personphone'
                                            }
                                        ]
                                    }
                                ]
                            }, {
                                region: 'south',
                                xtype: 'panel',
                                buttonAlign: 'center',
                                style: {
                                    borderTop: 'solid 1px #cecece'
                                },
                                buttons: [
                                    {
                                        glyph: 0xe86c,
                                        text: 'Salvar',
                                        showSmartTheme: 'red',
                                        handler: 'updateView'
                                    }, {
                                        glyph: 0xe875,
                                        text: 'Novo',
                                        showSmartTheme: 'red',
                                        handler: 'insertView'
                                    }, {
                                        glyph: 0xe869,
                                        text: 'Voltar',
                                        showSmartTheme: 'green',
                                        handler: 'onHistoryBack'
                                    }
                                ]
                            }
                        ]
                    }, {
                        flex: 1,
                        xtype: 'container'
                    }
                ]
            }
        ]
    }

});