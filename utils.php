<?php

$apiBase = "https://hacker-news.firebaseio.com/v0/";

function get_top_stories(): array {
    global $apiBase;
    $topStories = file_get_contents($apiBase . "topstories.json");
    return json_decode($topStories);
}

function get_best_stories(): array {
    global $apiBase;
    $bestStories = file_get_contents($apiBase . "beststories.json");
    return json_decode($bestStories);
}

function get_new_stories(): array {
    global $apiBase;
    $newStories = file_get_contents($apiBase . "newstories.json");
    return json_decode($newStories);
}

function get_stories_id(string $ranking): array {
    $tmp = array();

    switch ($ranking) {
        case 'top':
            $tmp = get_top_stories();
            break;
        case 'best':
            $tmp = get_best_stories();
            break;
        default:
            $tmp = get_new_stories();
            break;
    }

    return $tmp;
}

function get_items(array &$stories_id): array {
    global $apiBase;
    $mh = curl_multi_init();
    $curl_array = array();
    
    foreach ($stories_id as $id) {
        $curl_array[$id] = curl_init();
        curl_setopt($curl_array[$id], CURLOPT_URL, $apiBase . "item/" . $id . ".json");
        curl_setopt($curl_array[$id], CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_array[$id], CURLOPT_SSL_VERIFYPEER, false);
        curl_multi_add_handle($mh, $curl_array[$id]);
    }
    
    $running = null;
    do {
        curl_multi_exec($mh, $running);
    } while ($running);
    
    foreach ($stories_id as $id) {
        $stories[] = json_decode(curl_multi_getcontent($curl_array[$id]));
        curl_multi_remove_handle($mh, $curl_array[$id]);
    }
    
    curl_multi_close($mh);

    return $stories;
}

function sort_stories(string &$sort, array $stories): void {
    global $stories;
    switch ($sort) {
        case 'z-a':
            usort($stories, function($a, $b) {
                return strcmp($b->title, $a->title);
            });
            break;
        case 'score':
            usort($stories, function($a, $b) {
                return $b->score - $a->score;
            });
            break;
        default:
            usort($stories, function($a, $b) {
                return strcmp($a->title, $b->title);
            });
            break;
    }
}

function print_stories(array &$stories) {
    foreach ($stories as $story) {
        $id = htmlspecialchars($story->id);
        $title = htmlspecialchars($story->title);
        $author = htmlspecialchars($story->by);
        $url = isset($story->url) ? htmlspecialchars($story->url) : "https://news.ycombinator.com/item?id=$id";
        $score =htmlspecialchars($story->score);
        echo <<< EOT
        <tr>
            <td>$id</td>
            <td><a href="https://news.ycombinator.com/item?id=$id" target="_blank" rel="noopener noreferer">$title</a></td>
            <td>$author</td>
            <td><a href="$url" target="_blank" rel="noopener noreferer">$url</a></td>
            <td>$score</td>
        </tr>
        EOT;
    }
}




