<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteContent;
use Illuminate\Http\Request;

class SiteContentController extends Controller
{
    public function index()
    {
        $contents = SiteContent::orderBy('section')->get();
        return view('pages.apps.site-content.index', compact('contents'));
    }

    public function create()
    {
        $availableSections = $this->getAvailableSections();
        return view('pages.apps.site-content.create', compact('availableSections'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'section' => 'required|string|unique:site_contents,section',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
        ]);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('frontend/images'), $imageName);
            $validated['image'] = $imageName;
        }
        
        SiteContent::create($validated);
        
        return redirect()->route('admin.site-content.index')
            ->with('success', 'Content created successfully!');
    }

    public function edit($id)
    {
        $content = SiteContent::findOrFail($id);
        return view('pages.apps.site-content.edit', compact('content'));
    }

    public function update(Request $request, $id)
    {
        $content = SiteContent::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
        ]);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($content->image && file_exists(public_path('frontend/images/' . $content->image))) {
                unlink(public_path('frontend/images/' . $content->image));
            }
            
            // Upload new image
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('frontend/images'), $imageName);
            $validated['image'] = $imageName;
        }
        
        $content->update($validated);
        
        return redirect()->route('admin.site-content.index')
            ->with('success', 'Content updated successfully!');
    }

    public function destroy($id)
    {
        $content = SiteContent::findOrFail($id);
        $content->delete();
        
        return redirect()->route('admin.site-content.index')
            ->with('success', 'Content deleted successfully!');
    }

    private function getAvailableSections()
    {
        return [
            'hero' => 'Hero Section',
            'info' => 'Information Section',
            'footer_about' => 'Footer About',
            'footer_contact' => 'Footer Contact',
            'custom' => 'Custom Section',
        ];
    }
}
