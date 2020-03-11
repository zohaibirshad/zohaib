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
        /*$orderBy = 'CASE WHEN plan_user.plan_id = 3 THEN 0 
              WHEN plan_user.plan_id = 2 THEN 1 
              WHEN plan_user.plan_id = 1 THEN 2 
              WHEN plan_user.plan_id = 0 THEN 3 
         END desc';*/


         /*select `conversations`.`id`, `messages`.`created_at` from `conversations` left join `messages` on `messages`.`conversation_id` = `conversations`.`id` left join `plan_user` on `plan_user`.`user_id` = `messages`.`participation_id` where `conversations`.`id` in (55, 56) group by `messages`.`conversation_id` 
 order by */

 /*select `conversations`.`id`, `messages`.`created_at` from `conversations` left join `messages` on `messages`.`conversation_id` = `conversations`.`id` left join `plan_user` on `plan_user`.`user_id` = `messages`.`participation_id` where `conversations`.`id` in (55, 56) group by `messages`.`conversation_id` order by CASE WHEN plan_user.plan_id = 3 THEN `messages`.`created_at` END desc, CASE WHEN plan_user.plan_id = 2 THEN `messages`.`created_at` END desc, CASE WHEN plan_user.plan_id = 1 THEN `messages`.`created_at` END desc, CASE WHEN plan_user.plan_id = 0 THEN `messages`.`created_at` END desc*/

        $orderBy = 'CASE WHEN plan_user.plan_id = 3 THEN `messages`.`created_at` END desc, CASE WHEN plan_user.plan_id = 2 THEN `messages`.`created_at` END desc, CASE WHEN plan_user.plan_id = 1 THEN `messages`.`created_at` END desc, CASE WHEN plan_user.plan_id = 0 THEN `messages`.`created_at` END desc';
        return Conversation::leftJoin('messages', function($join){
                $join->on('messages.conversation_id', '=', 'conversations.id');
            })
            ->leftJoin('plan_user', function($join){
                $join->on('plan_user.user_id', '=', 'messages.participation_id');
            })
            ->wherein('conversations.id', $this->participation->pluck('conversation.id'))
            ->select('conversations.id'. `messages`.`created_at`)
            ->groupBy('messages.conversation_id')
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