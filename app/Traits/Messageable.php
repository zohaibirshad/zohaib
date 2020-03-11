<?php

namespace App\Traits;

use App\Models\Conversation;
use App\Models\Participation;
use App\Models\MessageNotification;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait Messageable
{
    public function conversationsWithLastMessage()
    {
        return Conversation::wherein('id', $this->participation->pluck('conversation.id'))->with('participantsWithLastMessage')->get();
    }

    public function conversations()
    {
        /*return Conversation::wherein('id', $this->participation->pluck('conversation.id'))->latest()->with('participants', 'messages')->get();*/
        $orderBy = 'CASE WHEN plan_user.plan_id = 3 THEN 0 
              WHEN plan_user.plan_id = 2 THEN 1 
              WHEN plan_user.plan_id = 1 THEN 2 
              WHEN plan_user.plan_id = 0 THEN 3 
         END desc, messages_id desc ';
         
        return Conversation::leftJoin(
                \DB::raw('(SELECT messages.conversation_id, messages.participation_id, messages.created_at, MAX(messages.id) AS messages_id FROM messages GROUP BY messages.conversation_id) AS msg'),
                    'msg.conversation_id', '=', 'conversations.id'
            )
            ->leftJoin('plan_user', function($join){
                $join->on('plan_user.user_id', '=', 'msg.participation_id');
            })
            ->wherein('conversations.id', $this->participation->pluck('conversation.id'))
            //->select('conversations.id'. `messages`.`created_at`)
            ->select('conversations.id')
            //->groupBy('messages.conversation_id')
            ->orderByRaw($orderBy)
            //->orderBy('messages.created_at', 'desc')
            //->latest()
            ->with('participants', 'messages')->get();
    }

    public function chat_notifications()
    {
        $conversations = $this->conversations()->pluck('id');
        // \Log::error($conversations);
        $count = 0;
        foreach($conversations as $conversation){
            $notifications = MessageNotification::where('user_id', auth()->user()->id)
            ->where('conversation_id', $conversation)
            ->where('is_seen', 0)
            ->count();
            // \Log::error($notifications);
            $count = $count + $notifications;
        }
        return $count;
    }

    /**
     * @return HasMany
     */
    public function participation(): HasMany
    {
        return $this->hasMany(Participation::class);
    }

    public function joinConversation(Conversation $conversation)
    {
        $participation = new Participation([
            'user_id'   => $this->getKey(),
            'conversation_id'  => $conversation->getKey(),
        ]);

        $this->participation()->save($participation);
    }

    public function leaveConversation($conversationId)
    {
        $this->participation()->where([
            'user_id'   => $this->getKey(),
            'conversation_id'  => $conversationId,
        ])->delete();
    }
}