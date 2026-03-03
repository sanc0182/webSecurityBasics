<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Review;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

class CreateReview extends Component
{
    // These variables will store what the user types in the form
    public $product_name = '';
    public $review_text = '';
    public $category_id = '';

    // This function runs when the user clicks "Submit"
    public function save()
    {
        // 1. Validate: Make sure they didn't leave anything blank
        $this->validate([
            'product_name' => 'required|min:3',
            'review_text' => 'required|min:10',
            'category_id' => 'required|exists:categories,id',
        ]);

        // 2. Create: Save the review to the database
        // Modified for Demo - SQL Injection
        \DB::unprepared("INSERT INTO reviews (user_id, product_name, category_id, created_at, updated_at, review_text)
        VALUES (" . Auth::id() . ", '$this->product_name', '$this->category_id', datetime('now'), datetime('now'), '$this->review_text')");
        // 3. Reset: Clear the form
        $this->reset();

        // 4. Feedback: Show a success message (optional, but good UX)
        session()->flash('message', 'Review created successfully!');

        // Optional: Redirect them to the dashboard
        return redirect()->route('dashboard');
    }

    // This tells Laravel to use the dashboard layout (so you get the nav bar)
    #[Layout('components.layouts.app')]
    public function render()
    {
        // We pass "categories" to the view so the dropdown list works
        return view('livewire.create-review', [
            'categories' => Category::all()
        ]);
    }
}
