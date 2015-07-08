var CakeRatchet = new (function(config, ab) {
    console.log(ab);
    console.log(config);

    /** 
     * The connection
     * @var ab.Session
     */
    var connection = false;
    
    /**
     * The server address
     * @var String
     */
    var server = config.host + ':' + config.port;

    return {
        connection : function(onConnect, onClose) {
            if(connection == false) {
                var c = new ab.Session('ws://' + server,
                    function() {
                        connection = c;
                        if(typeof onConnect === "function") {
                            onConnect(connection);
                        }
                        console.log('WebSocket connection : Success');
                    },
                    function() {
                        if(typeof onClose === "function") onClose(connection);
                        console.warn('WebSocket connection : Closed');
                        connection = false;
                    },
                    {
                         'skipSubprotocolCheck': true
                    }
                )
            }
        },
        subscribe : connection.subscribe,
        conn : connection
    };
})(CakeRatchet, ab);