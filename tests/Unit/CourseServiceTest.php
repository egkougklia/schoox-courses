<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Course;
use App\Services\CourseService;
use App\Enums\CourseStatus;
use Tests\TestCase;

class CourseServiceTest extends TestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();

        $this->courseService = new CourseService;
    }

    public function test_course_index(): void
    {
        $courses = Course::factory()->count(2)->create();
        $this->assertDatabaseCount('courses', 2);
        $indexCourses = $this->courseService->index();
        $this->assertTrue($courses[0]->is($indexCourses[0]));
        $this->assertTrue($courses[1]->is($indexCourses[1]));
    }

    public function test_course_store(): void
    {
        $input = [
            'title' => 'test',
            'description' => 'test description',
            'is_premium' => false,
            'status' => CourseStatus::PUBLISHED,
            'tags' => ['test tag']
        ];
        $course = $this->courseService->store($input);
        $this->assertModelExists($course);
        $this->assertDatabaseCount('courses', 1);
        $this->assertEquals($course->tags[0]['name'], 'test tag');
        $this->assertDatabaseCount('tags', 1);
        $this->assertDatabaseHas('tags', [
            'name' => 'test tag',
        ]);
    }

    public function test_course_show(): void
    {
        $course = Course::factory()->hasTags(1)->create();
        $returnedCourse = $this->courseService->show($course);
        $this->assertTrue($course->is($returnedCourse));
        $this->assertEquals($course->tags, $returnedCourse->tags);
        $this->assertEquals(count($returnedCourse->tags), 1);
    }

    public function test_course_update(): void
    {
        $course = Course::factory()->hasTags(2)->create();
        $input = [
            'title' => 'test',
            'description' => 'test description',
            'is_premium' => false,
            'status' => CourseStatus::PUBLISHED,
            'tags' => ['test tag']
        ];
        $course = $this->courseService->update($input, $course);
        $this->assertModelExists($course);
        $this->assertDatabaseCount('courses', 1);
        $this->assertEquals($course->tags[0]['name'], 'test tag');
        $this->assertDatabaseCount('tags', 3);
        $this->assertDatabaseHas('tags', [
            'name' => 'test tag',
        ]);
    }

    public function test_course_update_keep_tags(): void
    {
        $course = Course::factory()->hasTags(1, ['name' => 'test 1'])->create();
        $this->assertDatabaseCount('courses', 1);
        $this->assertDatabaseCount('tags', 1);
        $this->assertDatabaseHas('tags', [
            'name' => 'test 1',
        ]);
        $input = [
            'title' => 'test',
            'description' => 'test description',
            'is_premium' => false,
            'status' => CourseStatus::PUBLISHED,
            'tags' => ['test 1', 'test 2']
        ];
        $course = $this->courseService->update($input, $course);
        $this->assertEquals($course->tags[0]['name'], 'test 1');
        $this->assertEquals($course->tags[1]['name'], 'test 2');
        $this->assertDatabaseCount('tags', 2);
        $this->assertDatabaseHas('tags', [
            'name' => 'test 1',
        ]);
        $this->assertDatabaseHas('tags', [
            'name' => 'test 2',
        ]);
    }

    public function test_course_patch(): void
    {
        $course = Course::factory()->hasTags(1)->create();
        $input = [
            'title' => 'test',
        ];
        $course = $this->courseService->patch($input, $course);
        $this->assertModelExists($course);
        $this->assertDatabaseCount('courses', 1);
        $this->assertEquals(count($course->tags), 1);
        $this->assertEquals($course->title, 'test');
        $this->assertDatabaseCount('tags', 1);
    }

    public function test_course_delete(): void
    {
        $course = Course::factory()->hasTags(1)->create();
        $this->courseService->destroy($course);
        $this->assertSoftDeleted($course);
    }
}
