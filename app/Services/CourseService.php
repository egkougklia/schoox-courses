<?php

namespace App\Services;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use App\Models\Tag;

class CourseService
{
    /**
     * Get all courses
     */
    public function index()
    {
        return Course::all();
    }

    /**
     * Create course based on request values, along with tags
     */
    public function store($requestFields)
    {
        $newCourse = Course::create($requestFields);

        $tagsToSave = $requestFields['tags'] ?? null;
        $this->saveTags($newCourse, $tagsToSave);
        
        return $newCourse;
    }

    /**
     * Display course
     */
    public function show(Course $course)
    {
        return $course;
    }

    /**
     * Update existing course based on request values, along with tags
     */
    public function update($requestFields, Course $course)
    {
        $course->update($requestFields);

        $tagsToSave = $requestFields['tags'] ?? null;
        $this->saveTags($course, $tagsToSave);

        return $course;
    }

    /**
     * Partially update existing course based on request values, along with tags
     */
    public function patch($requestFields, Course $course)
    {
        $course->update($requestFields);
        
        // only sync tags if the tags key exists
        if (array_key_exists('tags', $requestFields)) {
            $tagsToSave = $requestFields['tags'];
            $this->saveTags($course, $tagsToSave);
        }

        return $course;
    }

    /**
     * (Soft) delete course
     */
    public function destroy(Course $course)
    {
        $course->delete();
    }

    /**
     * Save tags in course.
     * The function creates any tags that don't already exist
     * and attaches all of the $tags in the specified $course.
     */
    private function saveTags(Course $course, $tags)
    {
        $tagIds = [];
        if ($tags) {
            // search for any tags that exist
            $existingTags = Tag::whereIn('name', $tags)->get();

            // get all tag names that don't already exist in the database
            $notSavedTags = array_diff($tags, $existingTags->pluck('name')->all());
            
            // create all new tags and add them to the $existingTags collection
            foreach ($notSavedTags as $tagName) {
                $newTag = Tag::create(['name' => $tagName]);
                $existingTags->push($newTag);
            }

            $tagIds = $existingTags->pluck('id')->all();
        }

        // sync the course's tags with the specified tag ids
        $course->tags()->sync($tagIds);

        // load the new tags in the course object
        $course->load('tags');
    }
}
