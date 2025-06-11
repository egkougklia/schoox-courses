<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Services\CourseService;
use App\Http\Requests\Courses\IndexCourseRequest;
use App\Http\Requests\Courses\StoreCourseRequest;
use App\Http\Requests\Courses\UpdateCourseRequest;
use App\Http\Requests\Courses\PatchCourseRequest;


class CourseController extends Controller
{
    private CourseService $courseService;
    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }
    
    /**
     * Get all Courses
     */
    public function index(IndexCourseRequest $request)
    {
        return $this->courseService->index($request->validated());
    }

    /**
     * Create a Course
     */
    public function store(StoreCourseRequest $request)
    {
        $newCourse = $this->courseService->store($request->validated());
        return $newCourse;
    }

    /**
     * Get Course
     */
    public function show(Course $course)
    {
        return $this->courseService->show($course);
    }

    /**
     * Update Course
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        $updatedCourse = $this->courseService->update($request->validated(), $course);
        return $updatedCourse;
    }

    /**
     * Patch Course
     */
    public function patch(PatchCourseRequest $request, Course $course)
    {
        $updatedCourse = $this->courseService->patch($request->validated(), $course);
        return $updatedCourse;
    }

    /**
     * Delete Course
     */
    public function destroy(Course $course)
    {
        $this->courseService->destroy($course);
        return response()->noContent();
    }
}
