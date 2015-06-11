/**
 * Created by Yarsoniy on 09.06.2015.
 */
Ext.define('Purchase', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id',  type: 'int'},
        {name: 'description',   type: 'string'},
        {name: 'price', type: 'float'}
    ]
});

var loadGrid = function(data) {
    var grid = Ext.getCmp('PurchaseGrid');
    grid.getStore().loadData(data);
};

var socketConnection = null;

var initWebSocket = function() {
    var handleMessage = function(message) {
        var encodedData = Ext.JSON.decode(message);
        if (encodedData.header) {
            switch (encodedData.header) {
                case 'loadRecords':
                    loadGrid(encodedData.body);
                    break;
                default:
                    break;
            }
        }
    };

    socketConnection = new WebSocket('ws://localhost:8080');
    socketConnection.onopen = function() {
        console.log('Connection is opened');
    };
    socketConnection.onmessage = function(event) {
        console.log('Server message: ' + event.data);
        handleMessage(event.data);
    };
    socketConnection.onclose = function(event) {
        if (event.wasClean) {
            console.log('Connection was closed');
        } else {
            console.log('Connection was interrupted');
        }
    };
    socketConnection.onerror = function(error) {
        console.log(error.message);
    };
};

var sendServerComand = function(comand, data) {
    var message = {
        comand: comand,
        data: data
    };
    var encodedData = Ext.JSON.encode(message);
    socketConnection.send(encodedData);
};

var openRecord = function(record, store) {
    var id = record.get('id');
    var isNew = (id == 0);
    var title = isNew ? 'Adding purchase' : 'Editing purchase';

    var sendRecord = function() {
        var description = this.down('[name="description"]').getValue();
        var price = this.down('[name="price"]').getValue();

        if (!description.trim()) {
            Ext.Msg.alert("Error", "Description is empty!");
            return false;
        }

        var data = {
            id: id,
            description: description,
            price: price
        };
        var comand = isNew ? 'addPurchase' : 'updatePurchase';
        sendServerComand(comand, data);
        return true;
    };

    var recordEditorWindow = Ext.create('Ext.window.Window', {
        modal: true,
        title: title,
        width: 400,
        height: 120,
        layout: {
            type: 'fit'
        },
        sendRecord: sendRecord,
        items: [
            {
                xtype: 'panel',
                flex: 1,
                layout: 'fit',
                tbar: [
                    {
                        text: 'OK',
                        handler: function() {
                            if (recordEditorWindow.sendRecord()) {
                                recordEditorWindow.destroy();
                            }
                        }
                    }
                ],
                items: [
                    {
                        xtype: 'container',
                        layout: {
                            type: 'vbox',
                            align: 'stretch'
                        },
                        margins: '5 5 5 5',
                        items: [
                            {
                                xtype: 'textfield',
                                name: 'description',
                                fieldLabel: 'Description',
                                value: record.get('description')
                            },
                            {
                                xtype: 'numberfield',
                                name: 'price',
                                fieldLabel: 'Price',
                                value: record.get('price')
                            }
                        ]
                    }
                ]
            }
        ]
    });
    recordEditorWindow.show();
};

var createGrid = function() {
    var addRecordHandler = function() {
        var newPurchase = Ext.create('Purchase', {
            id: 0,
            description: '',
            price: 0.0
        });

        openRecord(newPurchase);
    };

    var editRecordHandler = function(record) {
        openRecord(record);
    };

    var removeRecordHandler = function() {
        var grid = this.up('gridpanel');
        var selection = grid.getSelectionModel().getSelection();
        if (selection) {
            var record = selection[0];
            var data = {
                id: record.get('id')
            };
            sendServerComand('removePurchase', data);
        }
    };

    var purchasesGrid = {
        id: 'PurchaseGrid',
        xtype: 'gridpanel',
        tbar: [
            {
                text: '+ Add record',
                handler: addRecordHandler
            },
            '->',
            {
                text: '- Remove record',
                handler: removeRecordHandler
            }
        ],
        store: Ext.create('Ext.data.Store', {
            model: 'Purchase'
        }),
        columns: [
            {
                width: 50,
                xtype: 'rownumberer',
                text: '#'
            },
            {
                flex: 1,
                text: 'Description',
                dataIndex: 'description'
            },
            {
                width: 100,
                text: 'Price',
                dataIndex: 'price'
            }
        ],
        listeners: {
            itemdblclick: function(grid, record) {
                editRecordHandler(record)
            }
        }
    };

    return purchasesGrid;
};

Ext.onReady(function(){
    Ext.create('Ext.container.Viewport',
        {
            layout: {
                type: 'hbox',
                align: 'stretch'
            },
            items: [
                {
                    xtype: 'container',
                    flex: 1
                },
                {
                    xtype: 'panel',
                    width: 500,
                    title: 'Purchases',
                    layout: 'fit',
                    margins: '20 0 20 0',
                    items: [
                        createGrid()
                    ]
                },
                {
                    xtype: 'container',
                    flex: 1
                }
            ]
        }
    );
    initWebSocket();
});