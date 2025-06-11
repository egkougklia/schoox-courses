<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Course;
use App\Enums\CourseStatus;
use Tests\TestCase;

class CourseEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_courses(): void
    {
        $courses = Course::factory()->hasTags(1)->count(2)->create();
        $response = $this->get('/api/courses');

        $response->assertStatus(200)->assertJson($courses->toArray(), true);
    }

    public function test_get_course(): void
    {
        $course = Course::factory()->hasTags(1)->create();
        $response = $this->get('/api/courses/'.$course->id);

        $response->assertStatus(200)->assertJson($course->toArray(), true);
    }

    public function test_get_course_not_found(): void
    {
        $course = Course::factory()->hasTags(1)->create();
        $response = $this->get('/api/courses/1');

        $response->assertStatus(404);
    }

    public function test_create_course(): void
    {
        $input = [
            'title' => 'test title',
            'description' => 'test description',
            'is_premium' => true,
            'status' => CourseStatus::DRAFT->value,
            'tags' => ['test tag']
        ];
        $response = $this->postJson('/api/courses/', $input);

        // tags are returned as an array of objects
        $input['tags'] = [
            ['name' => 'test tag']
        ];

        $response->assertStatus(201)->assertJson($input);
    }

    public function test_create_course_validation_error(): void
    {
        $input = [
            'title' => 'test title',
            'is_premium' => "fail",
            'status' => 'test'
        ];
        $response = $this->postJson('/api/courses/', $input);

        $response->assertStatus(422)->assertInvalid([
            'description' => 'The description field is required',
            'is_premium' => 'The is premium field must be true or false',
            'status' => 'The selected status is invalid',
        ]);
    }

    public function test_create_course_xss_validation_error(): void
    {
        $invalidInputs = ['<h1>hello</h1>', 'alert("this is an attack");'];

        foreach($invalidInputs as $invalidTitle)
        {
            $input = [
                'title' => $invalidTitle,
                'description' => 'test description',
                'is_premium' => true,
                'status' => CourseStatus::DRAFT->value,
                'tags' => ['test tag']
            ];
            $response = $this->postJson('/api/courses/', $input);

            $response->assertStatus(422)->assertInvalid([
                'title' => 'The title must not contain HTML tags or other special characters',
            ]);
        }
    }

    public function test_update_course(): void
    {
        $input = [
            'title' => 'test title',
            'description' => 'test description',
            'is_premium' => true,
            'status' => CourseStatus::DRAFT->value,
            'tags' => ['test tag']
        ];
        $course = Course::factory()->hasTags(1)->create();
        $response = $this->putJson('/api/courses/' . $course->id, $input);

        $input['tags'] = [
            ['name' => 'test tag']
        ];

        $response->assertStatus(200)->assertJson($input);
    }

    public function test_update_course_validation_error(): void
    {
        $input = [
            'title' => 'test title',
            'is_premium' => "fail",
            'status' => 'test',
        ];
        $course = Course::factory()->hasTags(1)->create();
        $response = $this->putJson('/api/courses/' . $course->id, $input);

        $response->assertStatus(422)->assertInvalid([
            'description' => 'The description field is required',
            'is_premium' => 'The is premium field must be true or false',
            'status' => 'The selected status is invalid',
        ]);
    }

    public function test_patch_course(): void
    {
        $input = [
            'title' => 'test title',
        ];
        $course = Course::factory()->hasTags(1)->create();
        $response = $this->patchJson('/api/courses/' . $course->id, $input);

        $response->assertStatus(200)->assertJson($input);
    }

    public function test_patch_course_validation_error(): void
    {
        $input = [
            'is_premium' => "fail",
        ];
        $course = Course::factory()->hasTags(1)->create();
        $response = $this->patchJson('/api/courses/' . $course->id, $input);

        $response->assertStatus(422)->assertInvalid([
            'is_premium' => 'The is premium field must be true or false',
        ]);
    }

    public function test_delete_course(): void
    {
        $course = Course::factory()->hasTags(1)->create();
        $response = $this->delete('/api/courses/' . $course->id);

        $response->assertStatus(204);
    }
}
