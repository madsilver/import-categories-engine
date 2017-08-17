<?php
/**
 * Created by PhpStorm.
 * User: silver
 * Date: 13/08/17
 * Time: 18:03
 */

namespace Silver;

/**
 * Class Parser
 * @package Silver
 */
class Builder
{
    /**
     * @var string
     */
    protected $store;

    /**
     * @var int
     */
    protected $total;

    /**
     * @var array
     */
    protected $categories;

    /**
     * @var int
     */
    private $done = 1;

    /**
     * @var time
     */
    private $startTime;

    /**
     * Builder constructor.
     * @param string $store
     * @param int $total
     */
    public function __construct(string $store, int $total)
    {
        $this->store = $store;
        $this->total = $total - 1;
        $this->startTime = time();
        $this->category = array();
    }

    /**
     * @param array $category
     */
    public function setCategories(array $category)
    {
        $this->categories[] = $category;
    }

    /**
     * @param string $slug
     * @return int
     * @throws \Exception
     */
    private function getCategoryId(string $slug) : int
    {
        if(count($this->categories) > 0) {
            foreach(array_reverse($this->categories) as $category) { // as a stack
                if($category['slug'] == $slug) {
                    return $category['id'];
                }
            }
        }

        throw new \Exception('parent_id not found');
    }

    /**
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function payload(array $data) : array
    {
        if (empty($data['name'])) {
            throw new \Exception('Field name required');
        }

        $payload = [
            'store' => $this->makeSlug($this->store),
            'name' => $data['name'],
            'name_en' => $data['name'],
            'url_key' => $this->makeSlug($data['name'])
        ];

        //consulta do parent no bob por slug
        if($data['parent_id'] != "") {
            $slug = $this->makeSlug($data['parent_id']);
            $payload['parent_id'] = $this->getCategoryId($slug);
        }

        return $payload;
    }

    /**
     * @param string $text
     * @return string
     * @throws \Exception
     */
    private function makeSlug(string $text) : string
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);

        if (empty($text)) {
            throw new \Exception('Invalid slug');
        }

        return $text;
    }

    /**
     * @param int $size
     */
    public function progress(int $size=50) {

        if($this->done > $this->total) return;

        $perc = (double)($this->done/$this->total);

        $bar = floor($perc*$size);

        $status_bar = "\r[";
        $status_bar.= str_repeat("=", $bar);
        if($bar<$size){
            $status_bar.= ">";
            $status_bar.= str_repeat(" ", $size - $bar);
        } else {
            $status_bar.="=";
        }

        $disp = number_format($perc*100, 0);

        $status_bar.="] $disp%  $this->done/$this->total";

        $now = time();
        $rate = ($now - $this->startTime)/$this->done;
        $left = $this->total - $this->done;
        $eta = round($rate * $left, 2);
        $elapsed = $now - $this->startTime;

        $status_bar.= " remaining: ".number_format($eta)." sec  elapsed: ".number_format($elapsed)." sec";

        $this->done++;

        echo "$status_bar  ";
    }

}