<?php

class BuilderTest extends \PHPUnit\Framework\TestCase
{
    public function testUrlCategoryWithSlug()
    {
        $builder = new \Silver\Builder('store', 1);
        $payload = $builder->payload(array(
            'name' => 'cátẽgöry@nâmè',
            'parent_id' => ''
        ));

        $this->assertEquals($payload['url_key'], 'category-name');
    }
}