<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\View\View;

class NewsController extends Controller
{
    /**
     * Display a listing of the news.
     */
    public function index() : View
    {
        $allNews = News::orderBy('date', 'desc')->orderBy('id', 'desc')->get();

        return view('news', ['allNews' => $allNews]);
    }

    /**
     * Dedicated Admin News Management View
     */
    public function adminIndex() : View
    {
        $allNews = News::orderBy('date', 'desc')->orderBy('id', 'desc')->get();
        $newsCount = $allNews->count();

        return view('admin.news.index', compact('allNews', 'newsCount'));
    }

    /**
     * Display the specified news item.
     */
    public function show($id) : View
    {
        $newsbyId = News::find($id);

        if (!$newsbyId) {
            abort(404, 'News item not found');
        }

        return view('news_specific', ['newsbyId' => $newsbyId]);
    }

    /**
     * Store a newly created news item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'required|string|max:2000',
            'author' => 'nullable|string|max:255',
            'date' => 'required|date',
            'news_source' => 'nullable|url|max:500',
            'news_img_url' => 'nullable|string|max:1000',
            'news_image_file' => 'nullable|image|max:5120',
            'additional_img_urls' => 'nullable|string',
            'paragraphs' => 'nullable|array',
            'paragraph_text' => 'nullable|string',
            'paragraph_image_urls' => 'nullable|array',
            'paragraph_existing_images' => 'nullable|array',
        ]);

        $coverImage = null;

        // Handle uploaded cover image
        if ($request->hasFile('news_image_file')) {
            $image = $request->file('news_image_file');
            $imageName = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.]/', '', $image->getClientOriginalName());
            $destinationPath = public_path('assets/img/news');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $image->move($destinationPath, $imageName);
            $coverImage = 'assets/img/news/' . $imageName;
        } elseif (!empty($validated['news_img_url'])) {
            $coverImage = trim($validated['news_img_url']);
        }

        // Handle paragraphs
        $paragraphs = [];
        if (!empty($validated['paragraphs']) && is_array($validated['paragraphs'])) {
            foreach ($validated['paragraphs'] as $p) {
                $clean = trim((string) $p);
                if (!empty($clean)) {
                    $paragraphs[] = $clean;
                }
            }
        }

        if (empty($paragraphs) && !empty($validated['paragraph_text'])) {
            $splits = preg_split('/(\r\n\r\n+|\n\n+)/', trim($validated['paragraph_text']));
            foreach ($splits as $p) {
                $clean = trim($p);
                if (!empty($clean)) {
                    $paragraphs[] = $clean;
                }
            }
        }

        // Fallback to description if no paragraph entered
        if (empty($paragraphs)) {
            $paragraphs[] = $validated['description'];
        }

        // Process images attached to each paragraph
        $paragraphImgUrls = $request->input('paragraph_image_urls', []);
        $paragraphExistingImgs = $request->input('paragraph_existing_images', []);
        $images = [];

        foreach ($paragraphs as $idx => $p) {
            $img = null;

            // Check if a file was uploaded for this paragraph
            if ($request->hasFile("paragraph_image_files.{$idx}")) {
                $file = $request->file("paragraph_image_files.{$idx}");
                $fileName = time() . "_{$idx}_" . preg_replace('/[^a-zA-Z0-9_\.]/', '', $file->getClientOriginalName());
                $dest = public_path('assets/img/news');
                if (!file_exists($dest)) {
                    mkdir($dest, 0755, true);
                }
                $file->move($dest, $fileName);
                $img = 'assets/img/news/' . $fileName;
            } elseif (!empty($paragraphImgUrls[$idx])) {
                $img = trim($paragraphImgUrls[$idx]);
            } elseif (!empty($paragraphExistingImgs[$idx])) {
                $img = trim($paragraphExistingImgs[$idx]);
            }

            // Paragraph 0 fallback to top cover image if no paragraph image given
            if ($idx === 0 && empty($img) && !empty($coverImage)) {
                $img = $coverImage;
            }

            $images[$idx] = $img;
        }

        // Ensure index 0 has cover image if provided
        if (!empty($coverImage) && empty($images[0])) {
            $images[0] = $coverImage;
        }

        $hasAnyImage = false;
        foreach ($images as $im) {
            if (!empty($im)) {
                $hasAnyImage = true;
                break;
            }
        }

        $news = News::create([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'author' => !empty($validated['author']) ? $validated['author'] : (auth()->check() ? auth()->user()->name : 'GDGoC MMU Team'),
            'date' => $validated['date'],
            'news_source' => $validated['news_source'] ?? null,
            'news_img' => $hasAnyImage ? $images : null,
            'paragraph' => $paragraphs,
            'user_id' => auth()->id() ?? 1,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'News article published successfully!',
                'news' => $news,
            ]);
        }

        return redirect()->route('admin.news.index')->with('message', "News article '{$news->title}' published successfully!");
    }

    /**
     * Update the specified news item.
     */
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'required|string|max:2000',
            'author' => 'nullable|string|max:255',
            'date' => 'required|date',
            'news_source' => 'nullable|url|max:500',
            'news_img_url' => 'nullable|string|max:1000',
            'news_image_file' => 'nullable|image|max:5120',
            'keep_current_image' => 'nullable|string',
            'additional_img_urls' => 'nullable|string',
            'paragraphs' => 'nullable|array',
            'paragraph_text' => 'nullable|string',
            'paragraph_image_urls' => 'nullable|array',
            'paragraph_existing_images' => 'nullable|array',
        ]);

        $coverImage = null;

        // Handle uploaded cover image
        if ($request->hasFile('news_image_file')) {
            $image = $request->file('news_image_file');
            $imageName = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.]/', '', $image->getClientOriginalName());
            $destinationPath = public_path('assets/img/news');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $image->move($destinationPath, $imageName);
            $coverImage = 'assets/img/news/' . $imageName;
        } elseif (!empty($validated['news_img_url'])) {
            $coverImage = trim($validated['news_img_url']);
        } elseif ($request->input('keep_current_image') === '1' && !empty($news->news_img[0])) {
            $coverImage = $news->news_img[0];
        }

        // Handle paragraphs
        $paragraphs = [];
        if (!empty($validated['paragraphs']) && is_array($validated['paragraphs'])) {
            foreach ($validated['paragraphs'] as $p) {
                $clean = trim((string) $p);
                if (!empty($clean)) {
                    $paragraphs[] = $clean;
                }
            }
        }

        if (empty($paragraphs) && !empty($validated['paragraph_text'])) {
            $splits = preg_split('/(\r\n\r\n+|\n\n+)/', trim($validated['paragraph_text']));
            foreach ($splits as $p) {
                $clean = trim($p);
                if (!empty($clean)) {
                    $paragraphs[] = $clean;
                }
            }
        }

        if (empty($paragraphs)) {
            $paragraphs = is_array($news->paragraph) && !empty($news->paragraph) ? $news->paragraph : [$validated['description']];
        }

        // Process images attached to each paragraph
        $paragraphImgUrls = $request->input('paragraph_image_urls', []);
        $paragraphExistingImgs = $request->input('paragraph_existing_images', []);
        $images = [];

        foreach ($paragraphs as $idx => $p) {
            $img = null;

            if ($request->hasFile("paragraph_image_files.{$idx}")) {
                $file = $request->file("paragraph_image_files.{$idx}");
                $fileName = time() . "_{$idx}_" . preg_replace('/[^a-zA-Z0-9_\.]/', '', $file->getClientOriginalName());
                $dest = public_path('assets/img/news');
                if (!file_exists($dest)) {
                    mkdir($dest, 0755, true);
                }
                $file->move($dest, $fileName);
                $img = 'assets/img/news/' . $fileName;
            } elseif (!empty($paragraphImgUrls[$idx])) {
                $img = trim($paragraphImgUrls[$idx]);
            } elseif (!empty($paragraphExistingImgs[$idx])) {
                $img = trim($paragraphExistingImgs[$idx]);
            } elseif (isset($news->news_img[$idx]) && !empty($news->news_img[$idx])) {
                $img = $news->news_img[$idx];
            }

            // Paragraph 0 fallback to cover image
            if ($idx === 0 && empty($img) && !empty($coverImage)) {
                $img = $coverImage;
            }

            $images[$idx] = $img;
        }

        if (!empty($coverImage) && empty($images[0])) {
            $images[0] = $coverImage;
        }

        $hasAnyImage = false;
        foreach ($images as $im) {
            if (!empty($im)) {
                $hasAnyImage = true;
                break;
            }
        }

        $news->update([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'author' => !empty($validated['author']) ? $validated['author'] : $news->author,
            'date' => $validated['date'],
            'news_source' => $validated['news_source'] ?? null,
            'news_img' => $hasAnyImage ? $images : null,
            'paragraph' => $paragraphs,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'News article updated successfully!',
                'news' => $news,
            ]);
        }

        return redirect()->route('admin.news.index')->with('message', "News article '{$news->title}' updated!");
    }

    /**
     * Remove the specified news item.
     */
    public function destroy(Request $request, News $news)
    {
        $title = $news->title;

        // If local cover image exists, optionally remove it
        if (is_array($news->news_img) && !empty($news->news_img[0])) {
            $img = $news->news_img[0];
            if (str_starts_with($img, 'assets/img/news/')) {
                $path = public_path($img);
                if (file_exists($path)) {
                    @unlink($path);
                }
            }
        }

        $news->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "News article '{$title}' deleted successfully!",
            ]);
        }

        return redirect()->route('admin.news.index')->with('message', "News article '{$title}' removed.");
    }

    /**
     * Return news data as JSON for preview and modal editing.
     */
    public function getNewsJson(News $news)
    {
        return response()->json([
            'success' => true,
            'news' => [
                'id' => $news->id,
                'title' => $news->title,
                'category' => $news->category,
                'description' => $news->description,
                'author' => $news->author,
                'date' => $news->date ? $news->date->format('Y-m-d') : null,
                'date_formatted' => $news->date ? $news->date->format('F j, Y') : null,
                'news_source' => $news->news_source,
                'news_img' => $news->news_img,
                'cover_img' => !empty($news->news_img[0]) ? (str_starts_with($news->news_img[0], 'http') ? $news->news_img[0] : asset($news->news_img[0])) : null,
                'paragraph' => $news->paragraph,
            ]
        ]);
    }
}
