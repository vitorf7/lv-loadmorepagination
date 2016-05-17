<?php
class LoadMorePaginationTest extends TestCase
{
    /**
     * @test
     * @expectedException VitorF7\LoadMorePagination\ModelClassRequiredException
     * */
    public function it_should_throw_an_exception_if_no_model_is_provided()
    {
        $testClass = new TestClass;

        $testClass->paginatedLoadMore();
    }

    /** @test */
    public function it_should_return_an_array_for_the_results()
    {
        $post = $this->makePost();

        $results = $post->paginateLoadMore();

        $this->assertTrue(is_array($results));
    }

    /** @test */
    public function it_allows_other_classes_to_call_method_to_get_paginated_load_more()
    {
        $testClass = new TestClass;

        $results = $testClass->paginatedLoadMore(9, 3, new Post);

        $this->assertTrue(is_array($results));
    }

    /** @test */
    public function it_allows_a_model_to_call_the_paginate_load_more_statically_and_return_the_full_results()
    {
        $this->makePost();

        $results = Post::paginateLoadMore();

        $this->assertEquals(1, $results['total']);
    }

    /** @test */
    public function it_should_have_current_page_array_key()
    {
        $post = $this->makePost();

        $results = $post->paginateLoadMore();

        $this->assertArrayHasKey('current_page', $results);
    }

    /** @test */
    public function it_should_have_per_page_array_key()
    {
        $post = $this->makePost();

        $results = $post->paginateLoadMore();

        $this->assertArrayHasKey('per_page', $results);
    }

    /** @test */
    public function it_should_have_total_array_key()
    {
        $post = $this->makePost();

        $results = $post->paginateLoadMore();

        $this->assertArrayHasKey('total', $results);
    }

    /** @test */
    public function it_should_have_last_page_array_key()
    {
        $post = $this->makePost();

        $results = $post->paginateLoadMore();

        $this->assertArrayHasKey('last_page', $results);
    }

    /** @test */
    public function it_should_have_from_array_key()
    {
        $post = $this->makePost();

        $results = $post->paginateLoadMore();

        $this->assertArrayHasKey('from', $results);
    }

    /** @test */
    public function it_should_have_to_array_key()
    {
        $post = $this->makePost();

        $results = $post->paginateLoadMore();

        $this->assertArrayHasKey('to', $results);
    }

    /** @test */
    public function it_should_have_previous_page_url_array_key()
    {
        $post = $this->makePost();

        $results = $post->paginateLoadMore();

        $this->assertArrayHasKey('previous_page_url', $results);
    }

    /** @test */
    public function it_should_have_next_page_url_array_key()
    {
        $post = $this->makePost();

        $results = $post->paginateLoadMore();

        $this->assertArrayHasKey('next_page_url', $results);
    }

    /** @test */
    public function it_should_have_data_array_key()
    {
        $post = $this->makePost();

        $results = $post->paginateLoadMore();

        $this->assertArrayHasKey('data', $results);
    }

    /** @test */
    public function it_should_have_an_initial_per_page_of_9()
    {
        $post = $this->makePost();

        $results = $post->paginateLoadMore();

        $this->assertEquals(9, $results['per_page']);
    }

    /** @test */
    public function it_should_start_on_page_1()
    {
        $post = $this->makePost();

        $results = $post->paginateLoadMore();

        $this->assertEquals(1, $results['current_page']);
    }

    /** @test */
    public function it_should_have_a_have_a_last_page_of_1_when_it_does_not_have_enough_for_more_than_one_page()
    {
        $post = $this->makePost();

        $results = $post->paginateLoadMore();

        $this->assertEquals(1, $results['last_page']);
    }

    /** @test */
    public function it_should_not_have_to_key_with_more_than_the_total_number_of_items()
    {
        $post = $this->makePost();

        $results = $post->paginateLoadMore();

        $this->assertEquals(1, $results['to']);
    }

    /** @test */
    public function it_should_have_2_pages_if_it_has_10_items_and_uses_default_options()
    {
        foreach (range(1, 10) as $number) {
            $this->makePost();
        }

        $results = Post::paginateLoadMore();

        $this->assertEquals(10, $results['total']);
        $this->assertEquals(2, $results['last_page']);
    }

    /** @test */
    public function it_should_have_3_pages_if_it_has_15_items_and_uses_default_options()
    {
        foreach (range(1, 15) as $number) {
            $this->makePost();
        }

        $results = Post::paginateLoadMore();

        $this->assertEquals(15, $results['total']);
        $this->assertEquals(3, $results['last_page']);
    }

    /** @test */
    public function it_should_have_3_pages_if_given_initial_value_3_and_load_more_3_in_a_result_set_of_9()
    {
        foreach (range(1, 9) as $number) {
            $this->makePost();
        }

        $results = Post::paginateLoadMore(3, 3);

        $this->assertEquals(9, $results['total']);
        $this->assertEquals(3, $results['last_page']);
    }
}

class TestClass
{
    use \VitorF7\LoadMorePagination\LoadMorePagination;
}
