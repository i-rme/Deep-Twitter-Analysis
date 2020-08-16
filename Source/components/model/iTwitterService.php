<?php

interface iTwitterService
{

    public function getTweets($limit = null, $order = null): array;
    public function getTweetsByUser($limit = null, $order = null, $user = null): array;
    public function getTweetsBySearch($limit = null, $order = null, $search = null): array;

    public function updateTweets($tweets);
    public function updateTweet($tweet);

    public function getListPopularUsers($limit = null, $order = null): string;
    public function getListPopularTweets($limit = null, $order = null): string;


    public function getUsers($limit = null, $order = null): array;
    public function getUsersBySearch($limit = null, $order = null, $search = null): array;

    public function updateUsers($users);
    public function updateUser($user);

}