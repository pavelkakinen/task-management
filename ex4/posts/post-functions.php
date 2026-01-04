<?php


include_once 'Post.php';

const DATA_FILE = 'posts.txt';

savePost(new Post(null, 'titel', 'textzuzu'));
print postsToString(getAllPosts());


function getAllPosts() : array {
    $result = [];

    $lines = file(DATA_FILE);

    foreach ($lines as $line) {
        [$id, $title, $text] = explode(';', trim($line));
        $result[] = new Post(urldecode($id), urldecode($title), urldecode($text));
    }

    return $result;
}

function savePost(Post $post): string {

    if ($post->id) {
        deletePostById($post->id);
    }

    $post->id = $post->id ?? getNewId();

    file_put_contents(DATA_FILE, postToTextLine($post), FILE_APPEND);

    return $post->id;

}

function postToTextLine(Post $post) : string
{
    return urlencode($post->id) . ';' . urlencode($post->title) . ';' . urlencode($post->text) . PHP_EOL;
}
function deletePostById(string $id): void {
    $posts = getAllPosts();
    $lines = [];
    foreach ($posts as $post) {
        if ($post->id !== $id) {
            $lines[] = postToTextLine($post);
        }
    }

    file_put_contents(DATA_FILE, implode('', $lines));
}

function getNewId(): string {
    $id = file_get_contents('next-id.txt');
    file_put_contents('next-id.txt', intval($id + 1));
    return $id;
}

function postsToString(array $posts): string {
    $result = '';
    foreach ($posts as $post) {
        $result .= $post . PHP_EOL;
    }
    return $result;
}
