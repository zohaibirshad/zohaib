@extends('layouts.dashboard_master')
@section('title', 'Messages')
@section('content')

<script src="{{ asset('js/websocket.js') }}"></script>

<!-- Messages Container -->
<div id='app'>
<div class="messages-container margin-top-0">
	<div class="messages-container-inner">

	<!-- Messages -->
	<div class="messages-inbox">
		<div class="messages-headline">
			<!-- <div class="input-with-icon">
					<input id="autocomplete-input" type="text" placeholder="Search">
				<i class="icon-material-outline-search"></i>
			</div> -->
		</div>

		<ul>
		@forelse ($conversations as $c)
			@foreach($c->participants as $p)
				@if($p->user->id != auth()->user()->id)
					<li>
						<a class="cursor-pointer" @click="findConversation({{ $c->id }}, {{ $p->user->profile }}, {{ $c->job_id }})">
							<div class="message-avatar">
								<i class="status-icon status-offline"></i>
								@if (sizeof($p->user->profile->getMedia('profile')) == 0)
								<img src="{{ asset('assets/images/user-avatar-placeholder.png') }}" alt="">
								@else
									<img src="{{ $p->user->profile->getFirstMediaUrl('profile', 'big') }}" alt=""/> 
								@endif
							</div>
							<div class="message-by">
								<div class="message-by-headline">
									<h5>{{ $p->user->name }}</h5>
									@if($p->conversationWithLastMessage->last_message)
									<span>{{ $p->conversationWithLastMessage->last_message->created_at->diffForHumans() }}</span>
									@else
									<span>{{ $p->created_at->diffForHumans() }}</span>
									@endif
								</div>
								@if($p->conversationWithLastMessage->last_message)
								<p>{{ $p->conversationWithLastMessage->last_message->body }}</p>
								@else
								<p>No Messages yet</p>
								@endif
							</div>
						</a>
					</li>
				@endif
			@endforeach
		@empty
			<p class="text-center text-muted py-3">Zero Conversation.. its quiet out here</p>
			
		@endforelse
        </ul>
	</div>
	<!-- Messages / End -->

	<!-- Message Content -->
	<div class="message-content">

		<div class="messages-headline">
			<h4>@{{ profile.name }}</h4>
			<!-- <span  @click="scrollDown()" class="message-action"><i class="icon-feather-arrow-down-circle cursor-pointer"></i> Go to Bottom</span> -->
		</div>
		<!-- Message Content Inner -->
			<div class="message-content-inner" v-chat-scroll="{always: true, smooth: true, scrollonremoved:true, smoothonremoved: true}">
				<div v-for="message in conversation">					
				<!-- Time Sign -->
				<div class="message-time-sign">
					<span>@{{ dateFormat(message.created_at) }}</span>
				</div>
				<div v-if="message.sender.email == user.email" class="message-bubble  me">
					<div class="message-bubble-inner ">
						<div :title="message.sender.name" class="message-avatar"><img v-bind:src="image(message.sender.profile)" alt=""/>
						 </div>
						<div class="message-text bg-light-blue-400">
							<p v-if="message.body">@{{ message.body }}</p>
							<div v-if="showAttachemnts(message.media)" class="attachments-container  flex flex-col">
								<div v-for="media_file in message.media">
									<a :href="file(media_file)" class="attachment-box bg-light-blue-400 ripple-effect cursor-pointer" download>
										<span class="text-capitalize text-white hover:text-black ">@{{ media_file.file_name }} </span>
										<i class="text-uppercase text-white hover:text-black">@{{ media_file.mime_type }}</i> 
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>

				<div v-else class="message-bubble">
					<div class="message-bubble-inner">
						<div :title="message.sender.name" class="message-avatar"><img  v-bind:src="image(message.sender.profile)" alt="" /></div>
						<div class="message-text">
							<p v-if="message.body">@{{ message.body }}</p>
							<div v-if="showAttachemnts(message.media)" class="attachments-container flex flex-col">
								<div v-for="media_file in message.media">
									<a :href="file(media_file)" target="new" class="attachment-box ripple-effect cursor-pointer" download>
										<span class="text-capitalize hover:text-white ">@{{ media_file.file_name }} </span>
										<i class="text-uppercase hover:text-white">@{{ media_file.mime_type }}</i> 
									</a>
								</div>
							</div>
						</div>
						<span id="pane"></span>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		
	<!-- Message Content Inner / End -->

					
<!-- Reply Area -->
<div v-show="canSendMessage" class="message-reply flex flex-row flex-wrap">
	<div class="flex flex-row flex-wrap">
		<div class="uploadButton margin-top-0">
			<input class="uploadButton-input" ref="files" v-on:change="handleFilesUpload()" type="file" accept="image/*, application/*, video/*" id="upload" name="files[]" multiple/>
			<label class="uploadButton-button ripple-effect" for="upload"><i class="icon-material-outline-attach-file"> </i></label>
			<div class="flex flex-col justify-start px-2">
				<span v-for="file in files">
					<div class="text-gray-700">@{{ file.name }}</div>
				</span>
			</div>
		</div>
	</div>   
	<textarea class="p-2 min-w-xxs" v-model="body"  cols="1" rows="1" placeholder="Your Message" data-autoresize></textarea>  
                           
	<button @click="send()"  class="button button-sliding-icon ripple-effect">
		<span v-if="!spinner">Send</span>
		<span id="spinner" v-if="spinner" class="spinner-border text-warning w-8 h-10 p-2"></span>
	</button>

</div>

</div>
	<!-- Message Content -->

	</div>

</div>
</div>

<!-- Messages Container / End -->

<script>

var conversations = <?= json_encode($conversations); ?>;
var user = <?= json_encode(auth()->user()); ?>;

Vue.config.devtools = true
Vue.use(VueChatScroll);
const app = window.app = new Vue({
	el: '#app',
	data:{
		conversations: conversations,
		single_conversation: {},
		user: user,
		profile: {},
		body: null,
		canSendMessage: false,
		job_id: '',
		files: null,
		spinner: false,
	},

	computed: {
		conversation: function(){
			return this.single_conversation;
		},

	},

	methods: {
		send(){
			var self = this;
			if(this.body == null & this.files == null){
				return alert("message can't be empty")
			}

			var formData = new FormData();
			if(this.files != null){
				for( var i = 0; i < this.files.length; i++ ){
					let file = this.files[i];

					formData.append('files[' + i + ']', file);
				}
			}
			if(this.body != null){
				formData.append('body', this.body);
			}
			
			if(this.single_conversation[0] != undefined){
				this.spinner = true;
				axios.post('../chats/'+ this.single_conversation[0].conversation_id,
					formData, {
						headers: {
						'Content-Type': 'multipart/form-data'
						}
					}
				).then(function(r){
					self.body = null;
					self.files = null;
					self.single_conversation.push(r.data);
					self.spinner = false;
					console.log(self.$refs.files.files);
				}).catch(function(){
					// console.log(e);
					self.spinner = false;
					
				})
			}else{
				this.body = null;
				this.files = null;
				alert("you can not send messages to user")
			}
		

		},

		showAttachemnts: function(media){
			if(media.length > 0)
            {
                return true; 
            }
		},

		file: function(media){
				var path = '../storage/' + media.id + '/' + media.file_name;
				console.log(path);
                return path; 
			
		},

		handleFilesUpload: function() {
			this.files = this.$refs.files.files;
			console.log(this.files);
			
		},

		markSeen(id){
			axios.put('../chats/'+ id + '/markseen/', {
			}).then(function(r){
				// console.log(r);
			}).catch(function(e){
				// console.log(e);
				
			})

		},

		dateFormat(d){
			return Moment(d).fromNow();
		},

		image(profile) {
		if (
			profile.photo == null ||
			profile.photo == "" ||
			profile.photo == undefined
		) {
			return "assets/images/user-avatar-placeholder.png";
		} else {
			return profile.photo;
		}
		},
		init: function(){
			var self = this
			var BreakException = {};
			if(conversations.length > 0){
				this.canSendMessage = true;
			}
			try {
				for (var index = 0; index < conversations.length; index++) {
					const element = conversations[index];
					element.participants.forEach(function(p) {
						if(p.user.id != self.user.id){
							// console.log(p);
							// console.log(element);
							
							self.single_conversation = element.messages;
							self.profile = p.user.profile
							self.job_id = element.job_id;
							throw BreakException;
						}	
					});
				}
			
			} catch (e) {
				if (e !== BreakException){
					throw e;
				} 
			}

			if(this.single_conversation[0] != undefined)
			{
				this.activateWebsocket()
				

			}else{
				this.canSendMessage = false;
				// this.scrollDown()
			}
			

		},
		findConversation:  function(id, profile, job_id){
			console.log(id);
			this.body = null;
			this.files = null;
			this.job_id = job_id;
			this.profile = profile;
			var self = this;
			this.conversations.forEach(function(c) {
				if(c.id == id){
					self.single_conversation = c.messages;
					// console.log(c);
				}	
			});

			if(this.single_conversation[0] != undefined)
			{
				this.activateWebsocket()
				
			}else{
				this.canSendMessage = false;
				this.scrollDown()
			}

			
			
		},

		activateWebsocket: function(){
			var self = this;

			this.markSeen(this.single_conversation[0].conversation_id);

			if(this.job_id == null){
				this.canSendMessage = false;
			}else{
				this.canSendMessage = true;
			}

			this.scrollDown();
			
			Echo.private('chat-conversation.' + this.single_conversation[0].conversation_id)
				.listen('MessageWasSent', function(e) {
					self.single_conversation.push(e.message);
					console.log(['websocket', e]);

			});

		
		},

		scrollDown: function(){
			console.log('clicked');
			
			var pane = document.getElementById('pane');
			if(pane != null){
				pane.scrollIntoView();
				pane.scrollTop = pane.scrollHeight;
			}
		
				
		}
	},

	mounted: function(){
		this.init()	
	}
});
</script>

@endsection