<?php
/**
 *
 */

namespace model\seo;
use model\post\Post_Data;

class SEO {

    private $content = null;
    private $seo_config;

    //private $dataType = null;
//    private $post_config = null;


    /**
     * @var Post_Data
     */
    private $post_data = null;


    public function __construct()
    {
        $this->seo_config = spyc_load_file( __ROOT_DIR__ . '/etc/seo.yaml');
        $this->seo_config['image'] = str_replace('{site_url}', get_site_url(), $this->seo_config['image']);
    }


    public function loadData() {



        $uri = substr($_SERVER['REQUEST_URI'], 1);

        if ( empty( $uri ) ) return;
        $segments = explode('/', $uri);


        if ( is_numeric( $segments[0] ) ) {
//            $this->dataType = 'post_data';
            $this->post_data = post( $segments[0] );
//            if ( $this->post_data->exist() ) {
//                $this->post_config = config( $this->post_data->idx );
//            }
        }
//        else {
//            $this->dataType = 'post_config';
//            $this->post_config = config( $uri );
//        }

    }

    public function getSiteName() {
        $c = &$this->seo_config;
        return $c['site_name'];
    }
    public function getTitle() {
        $c = &$this->seo_config;
        $title = $c['title'];
        if ( strpos( $title, '{site_name}' ) !== false ) {
            $title = str_replace("{site_name}", "{site_name}$c[padding_after_site_name]", $title);
            $title = str_replace("{site_name}", $this->getSiteName(), $title);
        }
        if ( strpos( $title, '{post_data.title}' ) !== false ) {
            if ( $this->post_data && $this->post_data->exist() ) {
                $title = str_replace("{post_data.title}", $this->post_data->getSafeTitle(), $title);
            }
            else {
                $title = str_replace("{post_data.title}", '', $title );
            }
        }
        $title = rtrim($title, ' -');
        return $title;
    }
    public function getDescription() {
        $c = &$this->seo_config;
        $description = $c['description'];
        if ( $this->post_data && $this->post_data->exist() ) {
            $description = $this->post_data->getSafeContent();
        }
        return $description;
    }
    public function getAuthor() {
        $c = &$this->seo_config;
        $author = $c['author'];
        if ( $this->post_data && $this->post_data->exist() ) {
            $author = $this->post_data->getUser()->getSafeName();
        }
        return $author;
    }
    public function getKeywords() {
        $c = &$this->seo_config;
        return $c['keywords'];
    }
    public function getImage() {
        $c = &$this->seo_config;
        //$image = $c['image'];
        $image = null;
        if ( $this->post_data && $this->post_data->exist() ) {
            $image = $this->post_data->file()->loadFirstImage()->url();
        }

        if ( empty($image) ) $image = $c['image'];

        return $image;
    }
    public function getUrl() {
        $c = &$this->seo_config;
        $url = $c['url'];
        if ( empty( $url ) ) $url = current_url();
        if ( $this->post_data ) $url = current_url();
        return $url;
    }


    public function getLinks() {

        $c = &$this->seo_config;


        $forum_links = null;
        $post_links = null;



        // SEO links no need forum list. Simply show posts.


        // If it is a post, it displays previous 20 posts
        // it can be a comment.
        $post_content = null;




        if ( $this->post_data && $this->post_data->exist() ) {
            $idx = $this->post_data->idx;


            $title = $this->post_data->title;
            $content = $this->post_data->content;
            if ( $this->post_data->updated ) $time = $this->post_data->updated;
            else $time = $this->post_data->created;
            $datetime = date("Y-m-d H:i", $time);


            $post_content .=<<<EOP
<main>
    <h1>$title</h1>
    <article>
        $content
    </article>
    <time datetime="$datetime">$datetime</time>
</main>
EOP;

            $posts = post()->getRecords( "idx<$idx ORDER BY idx DESC LIMIT 40", 'idx,title,content' );
        }
        else {
            $posts = post()->getRecords( '1 ORDER BY idx DESC LIMIT 40', 'idx,title,content' );
        }

        foreach( $posts as $post ) {
            $idx = $post['idx'];
            if ( empty($post['title']) ) $title = $post['content'];
            else $title = $post['title'];
            $title = strip_tags( $title );
            $title = strcut( $title, 250 );

            $post_links .= "<a href=\"/$idx/$title\">$title</a>";
        }


        return $post_content . $post_links;
    }

    /**
     * @return $this
     */
    public function patch() {

        $this->loadIndexHTML();
        $this->loadData();

        $c = &$this->seo_config;

        $site_name = $this->getSiteName();
        $title = $this->getTitle();
        $description = $this->getDescription();
        $author = $this->getAuthor();
        $keywords = $this->getKeywords();
        $image = $this->getImage();


        $url = $this->getUrl();



        // --------- SEO
        // title
        $this->replace("/<title>.*<\/title>/i", "<TITLE>$title</TITLE>");

        // description
        $this->replace("/<\/head>/i", "<meta name=\"description\" content=\"$description\">\n</head>");

        // keyword
        $this->replace("/<\/head>/i", "<meta name=\"keywords\" content=\"$keywords\">\n</head>");

        // author
        $this->replace("/<\/head>/i", "<meta name=\"author\" content=\"$author\">\n</head>");

        // --------- Schema.org markup for Google Plus
        $this->replace("/<\/head>/i", "<meta itemprop=\"name\" content=\"$title\">\n</head>");
        $this->replace("/<\/head>/i", "<meta itemprop=\"description\" content=\"$description\">\n</head>");
        $this->replace("/<\/head>/i", "<meta itemprop=\"image\" content=\"$image\">\n</head>");

        // --------- Twitter Cards
        // just use OG tags for twitter cards.



        // --------- Open Graph
        $this->replace("/<\/head>/i", "<meta property=\"og:site_name\" content=\"$site_name\">\n</head>");
        $this->replace("/<\/head>/i", "<meta property=\"og:type\" content=\"website\">\n</head>");
        $this->replace("/<\/head>/i", "<meta property=\"og:title\" content=\"$title\">\n</head>");
        $this->replace("/<\/head>/i", "<meta property=\"og:url\" content=\"$url\">\n</head>");
        $this->replace("/<\/head>/i", "<meta property=\"og:description\" content=\"$description\">\n</head>");
        $this->replace("/<\/head>/i", "<meta property=\"og:image\" content=\"$image\">\n</head>");




        // display forum/post links.
        // To avoid SEO robots exclude hidden links, it hides through Javascript.
        // On app side, For the first loads, it may blanking, but no blinking on the next load or page move. Especially if it is Single Page App, it really would not be a big matter.
        // @todo Be sure this hidden links are indexed by google robots.
$seo = <<<EOH
<div class="seo-links">{$this->getLinks()}</div>
<script>
    var __seo = 'seo';
    var __links = 'links';
    setTimeout(function(){
        document.querySelector( '.' + __seo + '-' + __links ).style.display = "none";
    }, 10);
</script>
EOH;

        $this->replace("/<\/body>/i", "$seo\n</body>");


        return $this;
    }


    public function replace( $pattern, $replace ) {
        $content = &$this->content;
        $content = preg_replace( $pattern, $replace, $content);
    }

    /**
     *
     */
    public function render() {

        echo $this->content;
    }


    public function loadIndexHTML() {

        $this->content = file_get_contents('index.html');

    }
}

