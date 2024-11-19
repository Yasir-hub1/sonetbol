<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function dashboard()
    {
        // Estadísticas generales
        $stats = [
            'total_products' => Product::count(),
            'total_users' => User::where('role_id', '!=', 1)->count(),
            'total_services' => ServiceRequest::count(),
            'pending_services' => ServiceRequest::where('status', 'pending')->count(),
            'low_stock_products' => Product::where('stock', '<=', 5)->count(),
            'total_categories' => Category::count(),
        ];

        // Productos con bajo stock
        $lowStockProducts = Product::where('stock', '<=', 5)
            ->with('category')
            ->get();

        // Servicios pendientes
        $pendingServices = ServiceRequest::where('status', 'pending')
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        // Últimos usuarios registrados
        $recentUsers = User::where('role_id', '!=', 1)
            ->latest()
            ->take(5)
            ->get();

        // Gráfico de servicios por estado
        $servicesByStatus = ServiceRequest::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Gráfico de servicios por mes (últimos 6 meses)
        $servicesByMonth = ServiceRequest::select(
            DB::raw("to_char(created_at, 'YYYY-MM') as month"),
            DB::raw('count(*) as total')
        )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Formatear los nombres de los meses para mejor visualización
        $servicesByMonth = $servicesByMonth->map(function ($item) {
            $date = Carbon::createFromFormat('Y-m', $item->month);
            return [
                'month' => $date->format('F Y'), // Nombre del mes y año
                'total' => $item->total
            ];
        });

        return view('admin.dashboard', compact(
            'stats',
            'lowStockProducts',
            'pendingServices',
            'recentUsers',
            'servicesByStatus',
            'servicesByMonth'
        ));
    }

    public function reports()
    {
        // Ventas por período
        $salesByPeriod = ServiceRequest::select(
            DB::raw("to_char(created_at, 'YYYY-MM') as period"),
            DB::raw('count(*) as total_services'),
            DB::raw('SUM(CASE WHEN status = \'completed\' THEN 1 ELSE 0 END) as completed_services')
        )
            ->groupBy('period')
            ->orderBy('period', 'desc')
            ->get();

        // Servicios más solicitados
        $popularServices = ServiceRequest::select('service_type', DB::raw('count(*) as total'))
            ->groupBy('service_type')
            ->orderBy('total', 'desc')
            ->get();

        // Productos más vistos
        $popularProducts = Product::select('products.*', DB::raw('count(*) as views'))
            ->leftJoin('product_views', 'products.id', '=', 'product_views.product_id')
            ->groupBy(
                'products.id',
                'products.name',
                'products.description',
                'products.price',
                'products.stock',
                'products.image_path',
                'products.created_at',
                'products.updated_at',
                'products.brand',
                'products.model',
                'products.specifications'
            )
            ->orderBy('views', 'desc')
            ->take(10)
            ->get();

        return view('admin.reports', compact(
            'salesByPeriod',
            'popularServices',
            'popularProducts'
        ));
    }
    public function profile()
    {
        return view('admin.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'required_with:new_password|password',
            'new_password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|max:1024'
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($request->filled('new_password')) {
            $user->password = bcrypt($validated['new_password']);
        }

        if ($request->hasFile('avatar')) {
            // Eliminar avatar anterior si existe
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->save();

        return back()->with('success', 'Perfil actualizado correctamente');
    }



    public function settings()
    {
        return view('admin.settings');
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'contact_email' => 'required|email',
            'support_phone' => 'required|string',
            'address' => 'required|string',
            'maintenance_mode' => 'boolean',
            'allow_registrations' => 'boolean'
        ]);

        foreach ($validated as $key => $value) {
            setting([$key => $value]);
        }

        return back()->with('success', 'Configuración actualizada correctamente');
    }

    public function systemLogs()
    {
        $logs = [];
        $logFile = storage_path('logs/laravel.log');

        if (file_exists($logFile)) {
            $logs = file_get_contents($logFile);
        }

        return view('admin.logs', compact('logs'));
    }

    public function clearLogs()
    {
        $logFile = storage_path('logs/laravel.log');

        if (file_exists($logFile)) {
            unlink($logFile);
        }

        return back()->with('success', 'Logs limpiados correctamente');
    }

    public function userManagement()
    {
        $users = User::with('role')
            ->where('role_id', '!=', 1)
            ->latest()
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function toggleUserStatus(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        return back()->with('success', 'Estado del usuario actualizado correctamente');
    }

    public function deleteUser(User $user)
    {
        if ($user->role_id === 1) {
            return back()->with('error', 'No se puede eliminar un usuario administrador');
        }

        $user->delete();

        return back()->with('success', 'Usuario eliminado correctamente');
    }

    public function backupDatabase()
    {
        // Implementar lógica de backup
        \Artisan::call('backup:run');

        return back()->with('success', 'Backup creado correctamente');
    }

    public function systemInfo()
    {
        $info = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'database_connection' => config('database.default'),
            'environment' => app()->environment(),
            'timezone' => config('app.timezone'),
            'memory_limit' => ini_get('memory_limit'),
            'max_upload_size' => ini_get('upload_max_filesize'),
            'max_execution_time' => ini_get('max_execution_time'),
            'server_ip' => $_SERVER['SERVER_ADDR'] ?? 'Unknown',
        ];

        return view('admin.system-info', compact('info'));
    }
}
