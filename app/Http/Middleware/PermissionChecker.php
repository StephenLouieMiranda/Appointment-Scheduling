<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PermissionChecker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permission = null)
    {
        $user = $request->user();
        $has_permission = $user->can($permission);

        $module = explode(".", $permission)[0] ?? '';

        if($has_permission) { return $next($request); }
        else{
            $message = $this->response_pool_message($permission);
            return $this->return_error( $message );
        }
        return $next($request);
    }

    private function response_pool_message($permission)
    {
        switch ($permission) {
            case 'accounts.*': $error_message = "You do not have permission to access the accounts module."; break;
            case 'accounts.read': $error_message = "You do not have permission to read account data."; break;
            case 'accounts.delete': $error_message = "You do not have permission to delete account data."; break;
            case 'accounts.add_role': $error_message = "You do not have permission to add roles to accounts."; break;
            case 'accounts.remove_role': $error_message = "You do not have permission to remove roles to accounts."; break;
            case 'accounts.add_admin_role': $error_message = "You do not have permission to add an admin role to accounts."; break;
            case 'accounts.add_permission' : $error_message = "You do not have the permission to add permissions to accounts."; break;
            case 'accounts.remove_permission' : $error_message = "You do not have the permission to remove permissions to accounts."; break;
            case 'appointments.*' : $error_message = "You do not have permission to access the appointments module."; break;
            case 'appointments.read' : $error_message = "You do not have permission to read appointment data."; break;
            case 'appointments.store' : $error_message = "You do not have permission to create an appointment."; break;
            case 'appointments.publish_link' : $error_message = "You do not have permission to publish link for appointment."; break;
            case 'appointments.update' : $error_message = "You do not have permission to update appointment data."; break;
            case 'appointments.cancel' : $error_message = "You do not have permission to cancel an appointment."; break;
            case 'patients.*' : $error_message = "You do not have permission to access the patients module."; break;
            case 'patients.store' : $error_message = "You do not have permission to create a new patient data."; break;
            case 'patients.read' : $error_message = "You do not have permission to read patient data."; break;
            case 'patients.udpate' : $error_message = "You do not have permission to update patient data."; break;
            case 'patients.delete' : $error_message = "You do not have permission to delete update patient data."; break;
            case 'medical_records.*' : $error_message = "You do not have permission to access the medical records module"; break;
            default:  $error_message = "An unknown permission error occured. "; break;
        }

        return $error_message;
    }

    private function return_error($message)
    {
        return response()->json([
            'api_code' => 'UNAUTHORIZED',
            'api_msg' => $message,
            'api_status' => FALSE,
        ], 401);
    }
}
