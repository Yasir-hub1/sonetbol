<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ServiceRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceRequestPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return true; // Todos los usuarios autenticados pueden ver la lista
    }

    public function view(User $user, ServiceRequest $serviceRequest)
    {
        return $user->id === $serviceRequest->user_id || $user->isAdmin();
    }

    public function create(User $user)
    {
        return !$user->isAdmin(); // Solo clientes pueden crear solicitudes
    }

    public function update(User $user, ServiceRequest $serviceRequest)
    {
        return $user->isAdmin();
    }

    public function delete(User $user, ServiceRequest $serviceRequest)
    {
        return $user->isAdmin();
    }

    public function updateStatus(User $user, ServiceRequest $serviceRequest)
    {
        return $user->isAdmin();
    }
}
