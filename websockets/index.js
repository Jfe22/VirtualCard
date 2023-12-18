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

  //transactions
  socket.on('newTransaction', (transaction) => {
    socket.broadcast.emit('newTransaction', transaction)
  })
  socket.on('editTransaction', (transaction) => {
    socket.broadcast.emit('editTransaction', transaction)
  })
  socket.on('deleteTransaction', (transaction) => {
    socket.broadcast.emit('deleteTransaction', transaction)
  })

  //vCards
  socket.on('newVCard', (vcard) => {
    socket.broadcast.emit('newVCard', vcard)
  })
  socket.on('editVCard', (vcard) => {
    socket.broadcast.emit('editVCard', vcard)
  })
  socket.on('deleteVCard', (vcard) => {
    socket.broadcast.emit('deleteVCard', vcard)
  })
  //updates userstore in realtime and blocks user if needed
  socket.on('vcardBlocked', (value) => {
    socket.broadcast.emit('vcardBlocked', value)
  })
  socket.on('vcardUnblocked', (value) => {
    socket.broadcast.emit('vcardUnblocked', value)
  })
  //change the balance in app
  //notifies the user if he receives money
  socket.on('balanceChange', (value) => {
    socket.broadcast.emit('balanceChange', value)
  })
})
