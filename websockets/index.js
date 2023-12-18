const httpServer = require('http').createServer()
const io = require("socket.io")(httpServer, {
	cors: {
		// The origin is the same as the Vue app domain. Change if necessary
//		origin: "http://localhost:5174",
    origin: "*",
		methods: ["GET", "POST"],
		credentials: true
	}
})

httpServer.listen(8080, () =>{
	console.log('listening on *:8080')
})

io.on('connection', (socket) => {
	console.log(`client ${socket.id} has connected`)

  //updates the transaction history in realtime
  socket.on('newTransaction', (transaction) => {
    socket.broadcast.emit('newTransaction', transaction)
  })

  //change the balance in app
  //notifies the user if he receives money
  socket.on('balanceChange', (value) => {
    socket.broadcast.emit('balanceChange', value)
  })

  //updates userstore in realtime and blocks user if needed
  socket.on('vcardBlocked', (value) => {
    socket.broadcast.emit('vcardBlocked', value)
  })
})
