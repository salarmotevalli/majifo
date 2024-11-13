<?php

namespace App\Enum;

enum ApprovalStatusEnum: int
{
    use EnumToArrayTrait;

    case DRAFT = 1;
    case PENDING = 2;
    case APPROVED = 3;

    public function string(): string
    {
        return match($this) 
        {
            Self::DRAFT => 'draft',   
            Self::PENDING => 'pending',   
            Self::APPROVED => 'approved',   
        };
    }

}
