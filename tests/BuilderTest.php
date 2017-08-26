<?php

class BuilderTest extends \PHPUnit\Framework\TestCase
{   
    public function testSlugCategory()
    {
        $builder = new \Silver\Builder('store', 1);
        $category = array(
            'name' => 'cátẽgöry@nâmè',
            'parent_id' => ''
        );
        $payload = $builder->payload($category);

        $this->assertEquals($payload['url_key'], 'category-name');
    }
    
    public function testShouldReturnIdOfCategory()
    {
        $builder = new \Silver\Builder('store', 1);
        $response = array(
            'id' => 1000,
            'slug' => 'category-parent'
        );
        $builder->setCategories($response);

        $category = array(
            'name' => 'category-name',
            'parent_id' => 'category-parent'
        );
        $payload = $builder->payload($category);
        
        $this->assertEquals($payload['parent_id'], 1000);
    }
}