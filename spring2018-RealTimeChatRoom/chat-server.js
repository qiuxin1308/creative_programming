// Require the packages we will use:
var http = require("http"),
	socketio = require("socket.io"),
	fs = require("fs");

//when the nodeJS server listen the request in 3456 port, the client.html page will open for user
// Listen for HTTP connections.  This is essentially a miniature static file server that only serves our one file, client.html:
var app = http.createServer(function(req, resp){
	// This callback runs when a new connection is made to our HTTP server.
	fs.readFile("client.html", function(err, data){
		// This callback runs when the client.html file has been read from the filesystem.
		if(err) return resp.writeHead(500);
		resp.writeHead(200);
		resp.end(data);
	});
});
app.listen(3456);

var users = [];
var usersList = {};
var socket_ids = []; 
var chatrooms = ['Public'];
var roomLists = {'Public':''};
var ownerLists = {};
var blockLists = {};

// Do the Socket.IO magic:
var io = socketio.listen(app); // No database, we do data transfer through socket.io
io.sockets.on("connection", function(socket){
	// This callback runs when a new Socket.IO connection is established.

	//listen 'message_to_server'
	socket.on('message_to_server', function(data) {
		// This callback runs when the server receives a new message from the client.
		io.sockets.in(socket.room).emit("message_to_client",{message:data["message"] })
	});

	
	socket.on('create_user_to_server', function(data){
		var username = data["username"]; 
		//console.log("user: " + data["username"]);
		var create_user_success = false;
		if(users.indexOf(username) > -1){  //username already exist. send false to client
			create_user_success = false;
			username += "0#$%&*"; //because this username is same with another one, in order to make a difference for real receiver
			socket.emit("create_user_to_client", {username: username, success: create_user_success});
		}else{
			//if success, return this information to username
			create_user_success = true;
			users.push(data["username"]);
			socket.curUser = data["username"];
			socket.room = 'Public';
			socket.join('Public');
			usersList[data["username"]] = socket.room;
			io.sockets.in(socket.room).emit("create_user_to_client", {success: create_user_success, allusers: usersList, curRoom: socket.room});
		}
	});

	socket.on('create_room_to_server', function(data){
		var roomname = data["roomname"];
		var password = data["password"];
		var owner = data["owner"];
		chatrooms.push(roomname);
		blockLists[roomname] = new Set();
		roomLists[roomname] = password;
		ownerLists[roomname] = owner;
		socket.emit("display_room_to_client",{chatrooms:chatrooms});
		socket.broadcast.emit("display_room_to_client",{chatrooms:chatrooms});
	});

	//send private message
	socket.on('sendPrivateMessage_server', function(data){
		var receiver = data["theUserName"];
		var sender = data["senderName"];
		var privateMsg = data["privateMsg"];
		console.log(privateMsg);
		socket.broadcast.emit("displayPrivateMsg_client",{receiver: receiver, sender: sender, privateMsg: privateMsg});
	});

	//enter the chat room
	socket.on('enterRoom_server', function(data){
		var matchPwd = data["matchPwd"];
		var roomPwd = roomLists[data["roomName"]];
		var oldRoom = socket.room;
		var curUser = socket.curUser;

		if (roomPwd == "") {
			socket.leave(oldRoom);
			socket.broadcast.to(oldRoom).emit("leftChatRoom_client",{curUser: curUser});
			socket.join(data["roomName"]);
			usersList[curUser] = data["roomName"];
			socket.room = data["roomName"];
		}else{
			if (roomPwd == matchPwd) {
				socket.leave(oldRoom);
				socket.broadcast.to(oldRoom).emit("leftChatRoom_client",{curUser: curUser});
				socket.join(data["roomName"]);
				usersList[curUser] = data["roomName"];
				socket.room = data["roomName"];
			}else{
				socket.emit("wrongPwd_client",{matchPwd:matchPwd});
			}
		}
		io.sockets.in(oldRoom).emit("create_user_to_client",{success: true, allusers: usersList, curRoom: oldRoom});
		io.sockets.in(socket.room).emit("create_user_to_client", {success: true, allusers: usersList, curRoom: socket.room});
	});

	//whether it is the owner of the room
	socket.on('kickBanPeopleIsOwner_server',function(data){
		var curUserName = data["curUserName"];
		var roomName = socket.room;
		var trueOwner = ownerLists[roomName];
		if (trueOwner == curUserName) {
			socket.emit("kickBanPeople_client",{isOwner: true, roomName: roomName});
		}else{
			socket.emit("kickBanPeople_client",{isOwner: false});
		}
	});

	//kick people in your room
	socket.on('kickPeopleByOwner_server',function(data){
		var kickedPeople = data["kickedPeople"];
		var curRoom = data["curRoom"];
		if (usersList[kickedPeople] == curRoom) {
			delete usersList[kickedPeople];
			io.sockets.in(curRoom).emit("create_user_to_client", {success:true, allusers: usersList, curRoom: curRoom});
			socket.emit("kickedSuccess",{kickedPeople: kickedPeople});
			socket.broadcast.to(curRoom).emit("getKickedPeople_client", {kickedPeople: kickedPeople, roomName: curRoom});
		}else{
			socket.emit("kickedFailed", {kickedPeople: kickedPeople});
		}
	});

	//the person who got kicked
	socket.on('peopleWhoGetKicked', function(data){
		socket.leave(data["roomName"]);
		socket.broadcast.to(data["roomName"]).emit("leftKickedRoom_client", {kickedPeople: data["kickedPeople"]});
		socket.join('Public');
		usersList[data["kickedPeople"]] = 'Public';
		socket.room = 'Public';
		io.sockets.in(socket.room).emit("create_user_to_client", {success:true, allusers: usersList, curRoom: socket.room});
	});

	//ban people in your room
	socket.on('banPeopleByOwner_server', function(data){
		var bannedPeople = data["bannedPeople"];
		var curRoom = data["curRoom"];
		if (usersList[bannedPeople] == curRoom) {
			blockLists[curRoom].add(bannedPeople);
			delete usersList[bannedPeople];
			io.sockets.in(curRoom).emit("create_user_to_client", {success:true, allusers: usersList, curRoom: curRoom});
			socket.emit("bannedSuccess", {bannedPeople: bannedPeople});
			socket.broadcast.to(curRoom).emit("getBannedPeople_client", {bannedPeople: bannedPeople, roomName: curRoom});
		}else{
			socket.emit("bannedFailed", {bannedPeople: bannedPeople});
		}
	});

	//the person who got banned
	socket.on('peopleWhoGetBanned', function(data){
		socket.leave(data["roomName"]);
		socket.broadcast.to(data["roomName"]).emit("leftBannedRoom_client", {bannedPeople: data["bannedPeople"]});
		socket.join('Public');
		usersList[data["bannedPeople"]] = 'Public';
		socket.room = 'Public';
		io.sockets.in(socket.room).emit("create_user_to_client", {success:true, allusers: usersList, curRoom: socket.room});
	});

	//the person who will be unblocked
	socket.on('unblockPeopleByOwner_server', function(data){
		var unblockPeople = data["unblockedUser"];
		var roomName = data["roomName"];
		if ((users.indexOf(unblockPeople) > -1) && (blockLists[roomName].has(unblockPeople))) {
			blockLists[roomName].delete(unblockPeople);
			socket.emit("unblockSuccess",{canUnblock: true, unblockPeople: unblockPeople});
			io.sockets.emit("getUnblockedPeople_client", {canUnblock: true, unblockPeople: unblockPeople, roomName: roomName});
		}else{
			socket.emit("unblockFail",{canUnblock: false, unblockPeople: unblockPeople});
			io.sockets.emit("getUnblockedPeople_client",{canUnblock: false, unblockPeople: unblockPeople, roomName: roomName});
		}
	});

	//ban message
	socket.on('banMessage_server', function(data){
		var banUser = data["banUser"];
		if (users.indexOf(banUser) > -1) {
			socket.emit("canBanMessage_client",{canBan: true, banUser: banUser});
		}else{
			socket.emit("canBanMessage_client", {canBan: false, banUser: banUser});
		}
	});
});
