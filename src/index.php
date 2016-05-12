<?php

namespace RedMatter\InterestingBlog;

use RedMatter\InterestingBlog\Entity\BlogPost;
use RedMatter\InterestingBlog\Entity\User;
use RedMatter\Templater;

$templater = new Templater('blog.html');

/** @var User $logged_in_user */
if (!$logged_in_user->validatePassword($_POST['password'])) {
    $templater->addSnippet('error.snippet.html', ['message' => 'Invalid password']);
}

$blog_post_repository = new Repository\BlogPostRepository();
$blog_post = new BlogPost();

$user_repository = new Repository\UserRepository();

$blog_post->user = $logged_in_user;
$blog_post->subject = $_POST['subject'];
$blog_post->message = $_POST['message'];

$blog_post_repository->save($blog_post);

// Notify all users of new post
$users = $user_repository->getAll();
array_walk($users, function (User $user) {
    // We only need the email addresses
    $user = $user->email_address;
});
mail(implode(', ', $users), 'New Post!', "{$logged_in_user->email_address} created a new post!");

// Display all posts, numbering them 1..n
$posts = $blog_post_repository->getAll();

$entry_number = 0;
foreach ($posts as $post) {
    $templater->addSnippet('blog.snippet.html', array(
        'entry_number' => $entry_number++,
        'user' => $post->user->email_address,
        'subject' => $post->subject,
        'message' => $post->message,
    ));
}

$templater->render();
