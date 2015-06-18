//@charset ISO-8859-1
Ext.define( 'AppAnest.view.users.UsersList', {
    extend: 'Ext.container.Container',

    xtype: 'userslist',

    requires: [
        'Ext.grid.Panel',
        'Ext.grid.column.Date',
        'Ext.button.Segmented',
        'Ext.grid.column.Widget',
        'Ext.layout.container.Card',
        'AppAnest.store.users.Users',
        'Ext.layout.container.SegmentedButton'
    ],

    controller: 'users',

    padding: 10,

    layout: {
        type: 'fit'
    },

    listeners: {
        afterrender: 'onFocusSearch'
    },

    initComponent: function () {
        var me = this;
        me.buildItems();
        me.callParent();
    },

    buildItems: function () {
        var me = this,
            usersStore = Ext.create('AppAnest.store.users.Users');

        me.items = [
            {
                frame: true,
                xtype: 'panel',

                glyph: 0xe80e,

                bodyStyle: 'padding: 30px 10px 10px 10px;',

                layout: {
                    type: 'hbox',
                    align: 'stretch'
                },
                header: {
                    title: 'Listar usuários',
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
                        xtype: 'container'
                    }, {
                        overflowY: 'auto',
                        width: 600,
                        xtype: 'panel',
                        layout: 'border',
                        items: [
                            {
                                bodyStyle: 'padding-bottom: 10px;',
                                layout: 'hbox',
                                region: 'north',
                                xtype: 'panel',
                                items: [
                                    {
                                        flex: 1,
                                        xtype: 'textfield',
                                        name: 'search',
                                        reference: 'search',
                                        hasSearch: true,
                                        listeners: {
                                            change: 'onSearchAlter'
                                        }
                                    }, {
                                        width: 5,
                                        xtype: 'splitter'
                                    }, {
                                        width: 106,
                                        xtype: 'segmentedbutton',
                                        defaults: {
                                            showSmartTheme: 'green',
                                            handler: 'getCardIndex'
                                        },
                                        items: [
                                            {
                                                cardIndex: 0,
                                                glyph: 0xe90e,
                                                pressed: true
                                            }, {
                                                cardIndex: 1,
                                                glyph: 0xe83a
                                            }, {
                                                cardIndex: 2,
                                                glyph: 0xe839
                                            }, {
                                                glyph: 0xe875,
                                                handler: 'insertViewNew'
                                            }
                                        ]
                                    }
                                ]
                            }, {
                                xtype: 'panel',
                                region: 'center',
                                layout: 'card',
                                bodyStyle: 'padding: 0 0 30px 0;',
                                reference: 'userViews',
                                dockedItems: [
                                    {
                                        xtype: 'pagingtoolbar',
                                        store: usersStore,
                                        dock: 'bottom',
                                        displayInfo: true
                                    }
                                ],
                                items: [
                                    {
                                        xtype: 'gridpanel',
                                        store: usersStore,
                                        listeners: {
                                            itemdblclick: 'onViewEdit'
                                        },
                                        columns: [
                                            {
                                                text: 'Nome',
                                                dataIndex: 'fullname',
                                                flex: 1,
                                                renderer: function (value,metaData,record) {
                                                    return  '<a class="smart-medium-users-title">'+value+'</a><br/>'+
                                                    '<a class="smart-medium-users-detail">' + record.get('username') +'</a><br/>'+
                                                    '<a class="smart-medium-users-detail" href="mailto:' + record.get('mainmail') + '">' + record.get('mainmail') + '</a>';
                                                }
                                            }, {
                                                text: 'Nasceu',
                                                dataIndex: 'birthdate',
                                                align: 'center',
                                                width: 120,
                                                xtype: 'datecolumn',
                                                renderer: function (value,metaData,record) {
                                                    return  '<a class="smart-medium-users-detail">'+Ext.util.Format.date(Ext.Date.parse(value,"Y-m-d"),'d/m/Y')+'</a>';
                                                }
                                            }, {
                                                header: 'Cadastro',
                                                width: 80,
                                                cls: 'smart-widgetcolumn',
                                                xtype: 'widgetcolumn',
                                                widget: {
                                                    width: 70,
                                                    xtype: 'button',
                                                    text: 'Editar',
                                                    glyph: 0xe85e,
                                                    showSmartTheme: 'red',
                                                    handler: 'onViewEdit'
                                                }
                                            }
                                        ]
                                    }, {
                                        xtype: 'gridpanel',
                                        store: usersStore,
                                        listeners: {
                                            itemdblclick: 'onViewEdit'
                                        },
                                        columns: [
                                            {
                                                text: 'Foto',
                                                width: 70,
                                                dataIndex: 'filedata',
                                                renderer: function (value,metaData,record) {
                                                    var data = record.get('filedata');
                                                    return (data) ? '<div style="width: 50px; height: 50px;"><img class="smart-medium-users-filedata" src="'+record.get('filetype')+'"></div>' : null;
                                                }
                                            }, {
                                                text: 'Nome',
                                                dataIndex: 'fullname',
                                                flex: 1,
                                                renderer: function (value,metaData,record) {
                                                    return  '<a class="smart-medium-users-title">'+value+'</a><br/>'+
                                                            '<a class="smart-medium-users-detail">' + record.get('username') +'</a><br/>'+
                                                            '<a class="smart-medium-users-detail" href="mailto:' + record.get('mainmail') + '">' + record.get('mainmail') + '</a>';
                                                }
                                            }, {
                                                text: 'Nasceu',
                                                dataIndex: 'birthdate',
                                                align: 'center',
                                                width: 120,
                                                xtype: 'datecolumn',
                                                renderer: function (value,metaData,record) {
                                                    return  '<a class="smart-medium-users-detail">'+Ext.util.Format.date(Ext.Date.parse(value,"Y-m-d"),'d/m/Y')+'</a>';
                                                }
                                            }, {
                                                header: 'Cadastro',
                                                width: 80,
                                                cls: 'smart-widgetcolumn',
                                                xtype: 'widgetcolumn',
                                                widget: {
                                                    width: 70,
                                                    xtype: 'button',
                                                    text: 'Editar',
                                                    glyph: 0xe85e,
                                                    showSmartTheme: 'red',
                                                    handler: 'onViewEdit'
                                                }
                                            }
                                        ]
                                    }, {
                                        name: 'thumbview',
                                        xtype: 'dataview',
                                        store: usersStore,
                                        scrollable: true,
                                        multiSelect: false,
                                        cls: 'smart-dataview',
                                        overItemCls: 'x-item-over',
                                        itemSelector: 'div.thumb-wrap',
                                        emptyText: 'No images to display',
                                        tpl: [
                                            '<tpl for=".">',
                                                '<div class="thumb-wrap" id="{id}">',
                                                    '<div class="thumb"><img style="width: 150px; height: 150px;" src="{filetype}"></div>',
                                                    '<span>{username}</span>',
                                                    '<span>{mainmail}</span>',
                                                '</div>',
                                            '</tpl>'
                                        ],
                                        listeners: {
                                            itemdblclick: 'onViewEdit'
                                        }
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
        ];
    }

});