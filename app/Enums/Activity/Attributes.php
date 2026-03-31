<?php

namespace App\Enums\Activity;

use App\Traits\EnumValues;
use App\Traits\TranslatableEnum;

enum Attributes: string
{
    case EffectiveCommunicators = 'Effective Communicators (EC)';
    case IndependentLearners = 'Independent Learners (IDL)';
    case InformedCompetent = 'Informed and Professionally Competent (IPC)';
    case NoClassification = 'No need to classify';
    case PositiveFlexible = 'Positive and Flexible (PF)';
    case ProblemSolvers = 'Problem-solvers (PS)';
    case Responsible = 'Professional, Socially and Globally Responsible (PSG)';

    use EnumValues;
    use TranslatableEnum;
}
