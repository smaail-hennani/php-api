<?php

namespace App\Security\Voter;

use App\Entity\Company;
use App\Entity\Project;
use App\Entity\User;
use App\Entity\UserCompany;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class CompanyVoter extends Voter
{
    public const EDIT = 'POST_EDIT';
    public const VIEW = 'POST_VIEW';
    public const DELETE = 'POST_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
            && ($subject instanceof Company || $subject instanceof Project);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        if ($subject instanceof Company) {
            return false;
        //if ($subject === "Company") {
            return $this->canAccessCompany($attribute, $subject, $user);
        }

        if ($subject instanceof Project) {
            return $this->canAccessProject($attribute, $subject, $user);
        }


        /*

        $company = $subject;
        $userCompany = $this->getUserCompany($user, $company);
        //$userCompany = $company->getUserSocieties()->filter(function(UserSociety $userSociety) use ($user) {
        //    return $userCompany->getRole() === $user;
        //})->first();

        if (!$userCompany) {
            return false; // L'utilisateur n'appartient pas à cette société
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::VIEW:
                // logic to determine if the user can VIEW
                // return true or false
                return in_array($userCompany->getRole(), ['admin', 'manager', 'consultant']);
            case self::EDIT:
                // logic to determine if the user can EDIT
                // return true or false
                return in_array($userCompany->getRole(), ['admin', 'manager']);
                // break;

            case self::DELETE:
                return $userCompany->getRole() ===='admin';
                // break;
        }
        */

        return false;
    }

    private function canAccessCompany(string $attribute, Company $company, User $user): bool
    {
        // Récupérer la relation entre l'utilisateur et la société
        $userCompany = $this->getUserCompany($user, $company);

        if (!$userCompany) {
            return false; // L'utilisateur n'a pas de rôle dans cette société
        }

        switch ($attribute) {
            case self::VIEW:
                return in_array($userCompany->getRole(), ['admin', 'manager', 'consultant']);
            case self::EDIT:
                return in_array($userCompany->getRole(), ['admin', 'manager']);
            case self::DELETE:
                return $userCompany->getRole() === 'admin';
        }

        return false;
    }

    private function canAccessProject(string $attribute, Project $project, User $user): bool
    {
        // Récupérer la société liée au projet
        $company = $project->getCompany();
        return $this->canAccessCompany($attribute, $company, $user); // Déléguer les droits à la société
    }

    private function getUserCompany(User $user, Company $company): ?UserCompany
    {
        // Rechercher l'association entre l'utilisateur et la société dans la collection
        foreach ($user->getUserCompanies() as $userCompany) {
            if ($userCompany->getCompany() === $company) {
                return $userCompany;
            }
        }

        // Retourner null si l'utilisateur n'est pas associé à cette société
        return null;
    }
}
