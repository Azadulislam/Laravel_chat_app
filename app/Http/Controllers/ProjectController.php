<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::withCount('comments')->latest()->paginate(12);
        return view('projects.index', compact('projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_url' => 'required|url',
        ]);

        $project = Project::create([
            'user_id' => Auth::id(),
            'project_url' => $request->project_url,
        ]);

        return redirect()->route('projects.proxy', $project);
    }

    public function show(Project $project)
    {
        $approvedComments = $project->comments()->where('status','approved')->with(['user', 'replies' => function($query) {
            $query->where('status', 'approved')->with('user');
        }])->get();
        return view('projects.show', compact('project','approvedComments'));
    }

    public function proxy(Project $project)
    {
        try {
            $cacheKey = 'proxy_page_' . $project->id;
            $html = cache()->remember($cacheKey, 60, function() use ($project) {
                return Http::timeout(30)->get($project->project_url)->body();
            });
            
            $baseUrl = rtrim($project->project_url, '/');
            $parsedUrl = parse_url($baseUrl);
            $baseDomain = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
            if (isset($parsedUrl['port'])) {
                $baseDomain .= ':' . $parsedUrl['port'];
            }
            
            $assetProxyUrl = url('/projects/' . $project->id . '/asset-proxy?url=');
            $commentStoreUrl = route('projects.comments.store', $project);
            $project->load(['comments' => function($query) {
                $query->where('status', 'approved')->with(['user', 'replies' => function($q) {
                    $q->where('status', 'approved')->with('user');
                }]);
            }]);
            $approvedComments = $project->comments;
            $csrfToken = csrf_token();
            $loginUrl = route('login');
            $isLoggedIn = Auth::check() ? 'true' : 'false';
            
            // First replace absolute URLs
            $patterns = [
                '/href="(https?:\/\/[^"]+)"/i',
                '/src="(https?:\/\/[^"]+)"/i',
                '/href=\'(https?:\/\/[^\']+)\'/i',
                '/src=\'(https?:\/\/[^\']+)\'/i',
                '/url\(["\']?(https?:\/\/[^"\')]+)["\']?\)/i',
            ];
            
            foreach ($patterns as $pattern) {
                $html = preg_replace_callback($pattern, function($matches) use ($assetProxyUrl, $baseUrl) {
                    $originalUrl = $matches[1];
                    $proxyUrl = $assetProxyUrl . urlencode($originalUrl);
                    return str_replace($originalUrl, $proxyUrl, $matches[0]);
                }, $html);
            }
            
            // Now handle all possible attributes that can have URLs
            $urlAttributes = ['href', 'src', 'srcset', 'action', 'data-src', 'data-href', 'poster'];
            foreach ($urlAttributes as $attr) {
                $html = preg_replace_callback('/(' . preg_quote($attr, '/') . ')=["\']([^"\']+)["\']/i', function($matches) use ($baseDomain, $assetProxyUrl, $baseUrl) {
                    $attrName = $matches[1];
                    $url = $matches[2];
                    
                    if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://') || str_starts_with($url, '//') || str_starts_with($url, 'data:') || str_starts_with($url, '#') || str_starts_with($url, 'mailto:')) {
                        return $matches[0];
                    }
                    
                    if (str_starts_with($url, '/')) {
                        $fullUrl = $baseDomain . $url;
                    } else {
                        $fullUrl = $baseUrl . '/' . ltrim($url, '/');
                    }
                    
                    $proxyUrl = $assetProxyUrl . urlencode($fullUrl);
                    return $attrName . '="' . $proxyUrl . '"';
                }, $html);
            }
            
            // Add base tag for dynamic relative URLs
            $baseTag = '<base href="' . $baseUrl . '/">';
            if (str_contains($html, '<head>')) {
                $html = preg_replace('/<head>/i', '<head>' . $baseTag, $html);
            } else {
                $html = $baseTag . $html;
            }
            
            $injection = view('comments.injection', [
                'approvedComments' => $approvedComments,
                'commentStoreUrl' => $commentStoreUrl,
                'csrfToken' => $csrfToken,
                'loginUrl' => $loginUrl,
                'isLoggedIn' => $isLoggedIn,
            ])->render();
            
            $html = preg_replace('/<\/body>/i', $injection . '</body>', $html);
            
            return response($html, 200)
                ->header('Content-Type', 'text/html');
        } catch (\Exception $e) {
            return response('Failed to load page: ' . $e->getMessage(), 500);
        }
    }
    
    public function assetProxy(Project $project, Request $request)
    {
        try {
            $decodedUrl = urldecode($request->query('url'));
            $parsedUrl = parse_url($decodedUrl);
            $path = $parsedUrl['path'] ?? '';
            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            
            $mimeTypes = [
                'css' => 'text/css',
                'js' => 'application/javascript',
                'json' => 'application/json',
                'png' => 'image/png',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'gif' => 'image/gif',
                'svg' => 'image/svg+xml',
                'ico' => 'image/x-icon',
                'woff' => 'font/woff',
                'woff2' => 'font/woff2',
                'ttf' => 'font/ttf',
                'eot' => 'application/vnd.ms-fontobject',
                'otf' => 'font/otf',
                'html' => 'text/html',
                'xml' => 'application/xml',
                'pdf' => 'application/pdf',
                'zip' => 'application/zip',
            ];

            $cacheKey = 'proxy_asset_' . md5($decodedUrl);
            $cachedData = cache()->remember($cacheKey, 60, function() use ($decodedUrl) {
                $response = Http::timeout(30)->get($decodedUrl);
                return [
                    'body' => $response->body(),
                    'upstream_content_type' => $response->header('Content-Type'),
                ];
            });

            // Determine correct MIME type
            if (isset($mimeTypes[$extension])) {
                $contentType = $mimeTypes[$extension];
            } elseif (str_starts_with($decodedUrl, 'https://fonts.googleapis.com/css')) {
                $contentType = 'text/css';
            } elseif (str_starts_with($decodedUrl, 'https://fonts.gstatic.com')) {
                $contentType = 'font/woff2'; // Google Fonts serve mostly woff2
            } else {
                $contentType = $cachedData['upstream_content_type'] ?? 'text/plain';
            }

            return response($cachedData['body'], 200)
                ->header('Content-Type', $contentType);
        } catch (\Exception $e) {
            return response('Failed to load asset: ' . $e->getMessage(), 500);
        }
    }
}
