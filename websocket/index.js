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
    console.log('New transaction with id: ' + transaction.id)
  })
  socket.on('editTransaction', (transaction) => {
    socket.broadcast.emit('editTransaction', transaction)
    console.log('Transaction with id: ' + transaction.id + ' was edited')
  })
  socket.on('deleteTransaction', (transaction) => {
    socket.broadcast.emit('deleteTransaction', transaction)
    console.log('Transaction with id: ' + transaction.id + ' was deleted')
  })


  //change the balance in app
  //notifies the user if he receives money
  socket.on('balanceChange', (value) => {
    socket.broadcast.emit('balanceChange', value)
  })


  //vCards
  socket.on('newVCard', (vCard) => {
    socket.broadcast.emit('newVCard', vCard)
    console.log('New vCard/User created with phone number: ' + vCard.phone_number)
  })
  socket.on('editVCard', (user) => {
    socket.broadcast.emit('editVCard', user)
    console.log('vCard/User with id: ' + user.id + ' was edited')
  })
  socket.on('deleteVCard', (vCard) => {
    socket.broadcast.emit('deleteVCard', vCard)
    console.log('vCard with phone number: ' + vCard.phone_number + ' was deleted')
  })

  //updates userstore in realtime and blocks user if needed
  socket.on('vcardBlocked', (vCard) => {
    socket.broadcast.emit('vcardBlocked', vCard)
    console.log('vCard with phone number: ' + vCard.phone_number + ' was blocked')
  })
  socket.on('vcardUnblocked', (vCard) => {
    socket.broadcast.emit('vcardUnblocked', vCard)
    console.log('vCard with phone number: ' + vCard.phone_number + ' was unblocked')
  })

})
