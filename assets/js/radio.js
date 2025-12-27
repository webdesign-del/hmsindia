$(function () {
  $('input[name=heart_attack_text]').hide();
  if ($('input[name=heart_attack]:checked').val() == "Yes") {
    $('input[name=heart_attack_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=heart_attack]:checked').val() == "Yes") {
      $('input[name=heart_attack_text]').show();
    } else if ($('input[name=heart_attack]:checked').val() == "No") {
      $('input[name=heart_attack_text]').hide();
    }
  });
  $('input[name=pacemaker_text]').hide();
  if ($('input[name=pacemaker]:checked').val() == "Yes") {
    $('input[name=pacemaker_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=pacemaker]:checked').val() == "Yes") {
      $('input[name=pacemaker_text]').show();
    } else if ($('input[name=pacemaker]:checked').val() == "No") {
      $('input[name=pacemaker_text]').hide();
    }
  });
  $('input[name=other_heart_disease_text]').hide();
  if ($('input[name=other_heart_disease]:checked').val() == "Yes") {
    $('input[name=other_heart_disease_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=other_heart_disease]:checked').val() == "Yes") {
      $('input[name=other_heart_disease_text]').show();
    } else if ($('input[name=other_heart_disease]:checked').val() == "No") {
      $('input[name=other_heart_disease_text]').hide();
    }
  });
  $('input[name=high_blood_pressure_text]').hide();
  if ($('input[name=high_blood_pressure]:checked').val() == "Yes") {
    $('input[name=high_blood_pressure_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=high_blood_pressure]:checked').val() == "Yes") {
      $('input[name=high_blood_pressure_text]').show();
    } else if ($('input[name=high_blood_pressure]:checked').val() == "No") {
      $('input[name=high_blood_pressure_text]').hide();
    }
  });
  $('input[name=blood_clots_text]').hide();
  if ($('input[name=blood_clots]:checked').val() == "Yes") {
    $('input[name=blood_clots_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=blood_clots]:checked').val() == "Yes") {
      $('input[name=blood_clots_text]').show();
    } else if ($('input[name=blood_clots]:checked').val() == "No") {
      $('input[name=blood_clots_text]').hide();
    }
  });
  $('input[name=chest_pain_text]').hide();
  if ($('input[name=chest_pain]:checked').val() == "Yes") {
    $('input[name=chest_pain_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=chest_pain]:checked').val() == "Yes") {
      $('input[name=chest_pain_text]').show();
    } else if ($('input[name=chest_pain]:checked').val() == "No") {
      $('input[name=chest_pain_text]').hide();
    }
  });
  $('input[name=stroke_text]').hide();
  if ($('input[name=stroke]:checked').val() == "Yes") {
    $('input[name=stroke_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=stroke]:checked').val() == "Yes") {
      $('input[name=stroke_text]').show();
    } else if ($('input[name=stroke]:checked').val() == "No") {
      $('input[name=stroke_text]').hide();
    }
  });
  $('input[name=asthma_text]').hide();
  if ($('input[name=asthma]:checked').val() == "Yes") {
    $('input[name=asthma_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=asthma]:checked').val() == "Yes") {
      $('input[name=asthma_text]').show();
    } else if ($('input[name=asthma]:checked').val() == "No") {
      $('input[name=asthma_text]').hide();
    }
  });
  $('input[name=lung_disease_text]').hide();
  if ($('input[name=lung_disease]:checked').val() == "Yes") {
    $('input[name=lung_disease_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=lung_disease]:checked').val() == "Yes") {
      $('input[name=lung_disease_text]').show();
    } else if ($('input[name=lung_disease]:checked').val() == "No") {
      $('input[name=lung_disease_text]').hide();
    }
  });
  $('input[name=lung_disease_text]').hide();
  if ($('input[name=other_lung_disease]:checked').val() == "Yes") {
    $('input[name=lung_disease_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=other_lung_disease]:checked').val() == "Yes") {
      $('input[name=lung_disease_text]').show();
    } else if ($('input[name=other_lung_disease]:checked').val() == "No") {
      $('input[name=lung_disease_text]').hide();
    }
  });
  $('input[name=difficulty_breathing_text]').hide();
  if ($('input[name=difficulty_breathing]:checked').val() == "Yes") {
    $('input[name=difficulty_breathing_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=difficulty_breathing]:checked').val() == "Yes") {
      $('input[name=difficulty_breathing_text]').show();
    } else if ($('input[name=difficulty_breathing]:checked').val() == "No") {
      $('input[name=difficulty_breathing_text]').hide();
    }
  });
  $('input[name=snoring_text]').hide();
  if ($('input[name=snoring]:checked').val() == "Yes") {
    $('input[name=snoring_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=snoring]:checked').val() == "Yes") {
      $('input[name=snoring_text]').show();
    } else if ($('input[name=snoring]:checked').val() == "No") {
      $('input[name=snoring_text]').hide();
    }
  });
  $('input[name=snoring_text]').hide();
  if ($('input[name=sleep_apnea]:checked').val() == "Yes") {
    $('input[name=snoring_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=sleep_apnea]:checked').val() == "Yes") {
      $('input[name=snoring_text]').show();
    } else if ($('input[name=sleep_apnea]:checked').val() == "No") {
      $('input[name=snoring_text]').hide();
    }
  });
  $('input[name=epilepsy_text]').hide();
  if ($('input[name=epilepsy]:checked').val() == "Yes") {
    $('input[name=epilepsy_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=epilepsy]:checked').val() == "Yes") {
      $('input[name=epilepsy_text]').show();
    } else if ($('input[name=epilepsy]:checked').val() == "No") {
      $('input[name=epilepsy_text]').hide();
    }
  });
  $('input[name=epilepsy_text]').hide();
  if ($('input[name=seizures]:checked').val() == "Yes") {
    $('input[name=epilepsy_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=seizures]:checked').val() == "Yes") {
      $('input[name=epilepsy_text]').show();
    } else if ($('input[name=seizures]:checked').val() == "No") {
      $('input[name=epilepsy_text]').hide();
    }
  });
  $('input[name=fainting_spells_text]').hide();
  if ($('input[name=fainting_spells]:checked').val() == "Yes") {
    $('input[name=fainting_spells_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=fainting_spells]:checked').val() == "Yes") {
      $('input[name=fainting_spells_text]').show();
    } else if ($('input[name=fainting_spells]:checked').val() == "No") {
      $('input[name=fainting_spells_text]').hide();
    }
  });
  $('input[name=diabetes_text]').hide();
  if ($('input[name=diabetes]:checked').val() == "Yes") {
    $('input[name=diabetes_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=diabetes]:checked').val() == "Yes") {
      $('input[name=diabetes_text]').show();
    } else if ($('input[name=diabetes]:checked').val() == "No") {
      $('input[name=diabetes_text]').hide();
    }
  });
  $('input[name=muscle_disorders_text]').hide();
  if ($('input[name=muscle_disorders]:checked').val() == "Yes") {
    $('input[name=muscle_disorders_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=muscle_disorders]:checked').val() == "Yes") {
      $('input[name=muscle_disorders_text]').show();
    } else if ($('input[name=muscle_disorders]:checked').val() == "No") {
      $('input[name=muscle_disorders_text]').hide();
    }
  });
  $('input[name=kidney_disease_text]').hide();
  if ($('input[name=kidney_disease]:checked').val() == "Yes") {
    $('input[name=kidney_disease_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=kidney_disease]:checked').val() == "Yes") {
      $('input[name=kidney_disease_text]').show();
    } else if ($('input[name=kidney_disease]:checked').val() == "No") {
      $('input[name=kidney_disease_text]').hide();
    }
  });
  $('input[name=hepatitis_text]').hide();
  if ($('input[name=hepatitis]:checked').val() == "Yes") {
    $('input[name=hepatitis_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=hepatitis]:checked').val() == "Yes") {
      $('input[name=hepatitis_text]').show();
    } else if ($('input[name=hepatitis]:checked').val() == "No") {
      $('input[name=hepatitis_text]').hide();
    }
  });
  $('input[name=tuberculosis_text]').hide();
  if ($('input[name=tuberculosis]:checked').val() == "Yes") {
    $('input[name=tuberculosis_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=tuberculosis]:checked').val() == "Yes") {
      $('input[name=tuberculosis_text]').show();
    } else if ($('input[name=tuberculosis]:checked').val() == "No") {
      $('input[name=tuberculosis_text]').hide();
    }
  });
  $('input[name=hiv_text]').hide();
  if ($('input[name=hiv]:checked').val() == "Yes") {
    $('input[name=hiv_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=hiv]:checked').val() == "Yes") {
      $('input[name=hiv_text]').show();
    } else if ($('input[name=hiv]:checked').val() == "No") {
      $('input[name=hiv_text]').hide();
    }
  });
  $('input[name=heart_burn_text]').hide();
  if ($('input[name=heart_burn]:checked').val() == "Yes") {
    $('input[name=heart_burn_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=heart_burn]:checked').val() == "Yes") {
      $('input[name=heart_burn_text]').show();
    } else if ($('input[name=heart_burn]:checked').val() == "No") {
      $('input[name=heart_burn_text]').hide();
    }
  });
  $('input[name=cancer_text]').hide();
  if ($('input[name=cancer]:checked').val() == "Yes") {
    $('input[name=cancer_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=cancer]:checked').val() == "Yes") {
      $('input[name=cancer_text]').show();
    } else if ($('input[name=cancer]:checked').val() == "No") {
      $('input[name=cancer_text]').hide();
    }
  });
  $('input[name=blood_disorders_text]').hide();
  if ($('input[name=blood_disorders]:checked').val() == "Yes") {
    $('input[name=blood_disorders_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=blood_disorders]:checked').val() == "Yes") {
      $('input[name=blood_disorders_text]').show();
    } else if ($('input[name=blood_disorders]:checked').val() == "No") {
      $('input[name=blood_disorders_text]').hide();
    }
  });
  $('input[name=rheumatic_disease_text]').hide();
  if ($('input[name=rheumatic_disease]:checked').val() == "Yes") {
    $('input[name=rheumatic_disease_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=rheumatic_disease]:checked').val() == "Yes") {
      $('input[name=rheumatic_disease_text]').show();
    } else if ($('input[name=rheumatic_disease]:checked').val() == "No") {
      $('input[name=rheumatic_disease_text]').hide();
    }
  });
  $('input[name=rheumatic_disease_text]').hide();
  if ($('input[name=psychiatric_disease]:checked').val() == "Yes") {
    $('input[name=rheumatic_disease_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=psychiatric_disease]:checked').val() == "Yes") {
      $('input[name=rheumatic_disease_text]').show();
    } else if ($('input[name=psychiatric_disease]:checked').val() == "No") {
      $('input[name=rheumatic_disease_text]').hide();
    }
  });
  $('input[name=psychiatric_disorder_text]').hide();
  if ($('input[name=psychiatric_disorder]:checked').val() == "Yes") {
    $('input[name=psychiatric_disorder_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=psychiatric_disorder]:checked').val() == "Yes") {
      $('input[name=psychiatric_disorder_text]').show();
    } else if ($('input[name=psychiatric_disorder]:checked').val() == "No") {
      $('input[name=psychiatric_disorder_text]').hide();
    }
  });
  $('input[name=thyroid_disorder_text]').hide();
  if ($('input[name=thyroid_disorder]:checked').val() == "Yes") {
    $('input[name=thyroid_disorder_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=thyroid_disorder]:checked').val() == "Yes") {
      $('input[name=thyroid_disorder_text]').show();
    } else if ($('input[name=thyroid_disorder]:checked').val() == "No") {
      $('input[name=thyroid_disorder_text]').hide();
    }
  });
  $('input[name=urinary_infection_text]').hide();
  if ($('input[name=urinary_infection]:checked').val() == "Yes") {
    $('input[name=urinary_infection_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=urinary_infection]:checked').val() == "Yes") {
      $('input[name=urinary_infection_text]').show();
    } else if ($('input[name=urinary_infection]:checked').val() == "No") {
      $('input[name=urinary_infection_text]').hide();
    }
  });
  $('input[name=sexually_transmitted_text]').hide();
  if ($('input[name=sexually_transmitted]:checked').val() == "Yes") {
    $('input[name=sexually_transmitted_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=sexually_transmitted]:checked').val() == "Yes") {
      $('input[name=sexually_transmitted_text]').show();
    } else if ($('input[name=sexually_transmitted]:checked').val() == "No") {
      $('input[name=sexually_transmitted_text]').hide();
    }
  });
  $('input[name=male_heart_attack_text]').hide();
  if ($('input[name=male_heart_attack]:checked').val() == "Yes") {
    $('input[name=male_heart_attack_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_heart_attack]:checked').val() == "Yes") {
      $('input[name=male_heart_attack_text]').show();
    } else if ($('input[name=male_heart_attack]:checked').val() == "No") {
      $('input[name=male_heart_attack_text]').hide();
    }
  });
  $('input[name=male_pacemaker_text]').hide();
  if ($('input[name=male_pacemaker]:checked').val() == "Yes") {
    $('input[name=male_pacemaker_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_pacemaker]:checked').val() == "Yes") {
      $('input[name=male_pacemaker_text]').show();
    } else if ($('input[name=male_pacemaker]:checked').val() == "No") {
      $('input[name=male_pacemaker_text]').hide();
    }
  });
  $('input[name=male_other_heart_disease_text]').hide();
  if ($('input[name=male_other_heart_disease]:checked').val() == "Yes") {
    $('input[name=male_other_heart_disease_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_other_heart_disease]:checked').val() == "Yes") {
      $('input[name=male_other_heart_disease_text]').show();
    } else if ($('input[name=male_other_heart_disease]:checked').val() == "No") {
      $('input[name=male_other_heart_disease_text]').hide();
    }
  });
  $('input[name=male_high_blood_pressure_text]').hide();
  if ($('input[name=male_high_blood_pressure]:checked').val() == "Yes") {
    $('input[name=male_high_blood_pressure_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_high_blood_pressure]:checked').val() == "Yes") {
      $('input[name=male_high_blood_pressure_text]').show();
    } else if ($('input[name=male_high_blood_pressure]:checked').val() == "No") {
      $('input[name=male_high_blood_pressure_text]').hide();
    }
  });
  $('input[name=male_blood_clots_text]').hide();
  if ($('input[name=male_blood_clots]:checked').val() == "Yes") {
    $('input[name=male_blood_clots_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_blood_clots]:checked').val() == "Yes") {
      $('input[name=male_blood_clots_text]').show();
    } else if ($('input[name=male_blood_clots]:checked').val() == "No") {
      $('input[name=male_blood_clots_text]').hide();
    }
  });
  $('input[name=male_chest_pain_text]').hide();
  if ($('input[name=male_chest_pain]:checked').val() == "Yes") {
    $('input[name=male_chest_pain_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_chest_pain]:checked').val() == "Yes") {
      $('input[name=male_chest_pain_text]').show();
    } else if ($('input[name=male_chest_pain]:checked').val() == "No") {
      $('input[name=male_chest_pain_text]').hide();
    }
  });
  $('input[name=male_stroke_text]').hide();
  if ($('input[name=male_stroke]:checked').val() == "Yes") {
    $('input[name=male_stroke_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_stroke]:checked').val() == "Yes") {
      $('input[name=male_stroke_text]').show();
    } else if ($('input[name=male_stroke]:checked').val() == "No") {
      $('input[name=male_stroke_text]').hide();
    }
  });
  $('input[name=male_asthma_text]').hide();
  if ($('input[name=male_asthma]:checked').val() == "Yes") {
    $('input[name=male_asthma_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_asthma]:checked').val() == "Yes") {
      $('input[name=male_asthma_text]').show();
    } else if ($('input[name=male_asthma]:checked').val() == "No") {
      $('input[name=male_asthma_text]').hide();
    }
  });
  $('input[name=male_lung_disease_text]').hide();
  if ($('input[name=male_lung_disease]:checked').val() == "Yes") {
    $('input[name=male_lung_disease_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_lung_disease]:checked').val() == "Yes") {
      $('input[name=male_lung_disease_text]').show();
    } else if ($('input[name=male_lung_disease]:checked').val() == "No") {
      $('input[name=male_lung_disease_text]').hide();
    }
  });
  $('input[name=male_lung_disease_text]').hide();
  if ($('input[name=male_other_lung_disease]:checked').val() == "Yes") {
    $('input[name=male_lung_disease_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_other_lung_disease]:checked').val() == "Yes") {
      $('input[name=male_lung_disease_text]').show();
    } else if ($('input[name=male_other_lung_disease]:checked').val() == "No") {
      $('input[name=male_lung_disease_text]').hide();
    }
  });
  $('input[name=male_difficulty_breathing_text]').hide();
  if ($('input[name=male_difficulty_breathing]:checked').val() == "Yes") {
    $('input[name=male_difficulty_breathing_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_difficulty_breathing]:checked').val() == "Yes") {
      $('input[name=male_difficulty_breathing_text]').show();
    } else if ($('input[name=male_difficulty_breathing]:checked').val() == "No") {
      $('input[name=male_difficulty_breathing_text]').hide();
    }
  });
  $('input[name=male_snoring_text]').hide();
  if ($('input[name=male_snoring]:checked').val() == "Yes") {
    $('input[name=male_snoring_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_snoring]:checked').val() == "Yes") {
      $('input[name=male_snoring_text]').show();
    } else if ($('input[name=male_snoring]:checked').val() == "No") {
      $('input[name=male_snoring_text]').hide();
    }
  });
  $('input[name=male_snoring_text]').hide();
  if ($('input[name=male_sleep_apnea]:checked').val() == "Yes") {
    $('input[name=male_snoring_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_sleep_apnea]:checked').val() == "Yes") {
      $('input[name=male_snoring_text]').show();
    } else if ($('input[name=male_sleep_apnea]:checked').val() == "No") {
      $('input[name=male_snoring_text]').hide();
    }
  });
  $('input[name=male_epilepsy_text]').hide();
  if ($('input[name=male_epilepsy]:checked').val() == "Yes") {
    $('input[name=male_epilepsy_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_epilepsy]:checked').val() == "Yes") {
      $('input[name=male_epilepsy_text]').show();
    } else if ($('input[name=male_epilepsy]:checked').val() == "No") {
      $('input[name=male_epilepsy_text]').hide();
    }
  });
  $('input[name=male_epilepsy_text]').hide();
  if ($('input[name=male_seizures]:checked').val() == "Yes") {
    $('input[name=male_epilepsy_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_seizures]:checked').val() == "Yes") {
      $('input[name=male_epilepsy_text]').show();
    } else if ($('input[name=male_seizures]:checked').val() == "No") {
      $('input[name=male_epilepsy_text]').hide();
    }
  });
  $('input[name=male_fainting_spells_text]').hide();
  if ($('input[name=male_fainting_spells]:checked').val() == "Yes") {
    $('input[name=male_fainting_spells_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_fainting_spells]:checked').val() == "Yes") {
      $('input[name=male_fainting_spells_text]').show();
    } else if ($('input[name=male_fainting_spells]:checked').val() == "No") {
      $('input[name=male_fainting_spells_text]').hide();
    }
  });
  $('input[name=male_diabetes_text]').hide();
  if ($('input[name=male_diabetes]:checked').val() == "Yes") {
    $('input[name=male_diabetes_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_diabetes]:checked').val() == "Yes") {
      $('input[name=male_diabetes_text]').show();
    } else if ($('input[name=male_diabetes]:checked').val() == "No") {
      $('input[name=male_diabetes_text]').hide();
    }
  });
  $('input[name=male_muscle_disorders_text]').hide();
  if ($('input[name=male_muscle_disorders]:checked').val() == "Yes") {
    $('input[name=male_muscle_disorders_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_muscle_disorders]:checked').val() == "Yes") {
      $('input[name=male_muscle_disorders_text]').show();
    } else if ($('input[name=male_muscle_disorders]:checked').val() == "No") {
      $('input[name=male_muscle_disorders_text]').hide();
    }
  });
  $('input[name=male_kidney_disease_text]').hide();
  if ($('input[name=male_kidney_disease]:checked').val() == "Yes") {
    $('input[name=male_kidney_disease_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_kidney_disease]:checked').val() == "Yes") {
      $('input[name=male_kidney_disease_text]').show();
    } else if ($('input[name=male_kidney_disease]:checked').val() == "No") {
      $('input[name=male_kidney_disease_text]').hide();
    }
  });
  $('input[name=male_hepatitis_text]').hide();
  if ($('input[name=male_hepatitis]:checked').val() == "Yes") {
    $('input[name=male_hepatitis_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_hepatitis]:checked').val() == "Yes") {
      $('input[name=male_hepatitis_text]').show();
    } else if ($('input[name=male_hepatitis]:checked').val() == "No") {
      $('input[name=male_hepatitis_text]').hide();
    }
  });
  $('input[name=male_tuberculosis_text]').hide();
  if ($('input[name=male_tuberculosis]:checked').val() == "Yes") {
    $('input[name=male_tuberculosis_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_tuberculosis]:checked').val() == "Yes") {
      $('input[name=male_tuberculosis_text]').show();
    } else if ($('input[name=male_tuberculosis]:checked').val() == "No") {
      $('input[name=male_tuberculosis_text]').hide();
    }
  });
  $('input[name=male_hiv_text]').hide();
  if ($('input[name=male_hiv]:checked').val() == "Yes") {
    $('input[name=male_hiv_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_hiv]:checked').val() == "Yes") {
      $('input[name=male_hiv_text]').show();
    } else if ($('input[name=male_hiv]:checked').val() == "No") {
      $('input[name=male_hiv_text]').hide();
    }
  });
  $('input[name=male_heart_burn_text]').hide();
  if ($('input[name=male_heart_burn]:checked').val() == "Yes") {
    $('input[name=male_heart_burn_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_heart_burn]:checked').val() == "Yes") {
      $('input[name=male_heart_burn_text]').show();
    } else if ($('input[name=male_heart_burn]:checked').val() == "No") {
      $('input[name=male_heart_burn_text]').hide();
    }
  });
  $('input[name=male_cancer_text]').hide();
  if ($('input[name=male_cancer]:checked').val() == "Yes") {
    $('input[name=male_cancer_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_cancer]:checked').val() == "Yes") {
      $('input[name=male_cancer_text]').show();
    } else if ($('input[name=male_cancer]:checked').val() == "No") {
      $('input[name=male_cancer_text]').hide();
    }
  });
  $('input[name=male_blood_disorders_text]').hide();
  if ($('input[name=male_blood_disorders]:checked').val() == "Yes") {
    $('input[name=male_blood_disorders_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_blood_disorders]:checked').val() == "Yes") {
      $('input[name=male_blood_disorders_text]').show();
    } else if ($('input[name=male_blood_disorders]:checked').val() == "No") {
      $('input[name=male_blood_disorders_text]').hide();
    }
  });
  $('input[name=male_rheumatic_disease_text]').hide();
  if ($('input[name=male_rheumatic_disease]:checked').val() == "Yes") {
    $('input[name=male_rheumatic_disease_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_rheumatic_disease]:checked').val() == "Yes") {
      $('input[name=male_rheumatic_disease_text]').show();
    } else if ($('input[name=male_rheumatic_disease]:checked').val() == "No") {
      $('input[name=male_rheumatic_disease_text]').hide();
    }
  });
  $('input[name=male_rheumatic_disease_text]').hide();
  if ($('input[name=male_rheumatic_disease]:checked').val() == "Yes") {
    $('input[name=male_rheumatic_disease_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_rheumatic_disease]:checked').val() == "Yes") {
      $('input[name=male_rheumatic_disease_text]').show();
    } else if ($('input[name=male_rheumatic_disease]:checked').val() == "No") {
      $('input[name=male_rheumatic_disease_text]').hide();
    }
  });
  $('input[name=male_psychiatric_disorder_text]').hide();
  if ($('input[name=male_psychiatric_disorder]:checked').val() == "Yes") {
    $('input[name=male_psychiatric_disorder_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_psychiatric_disorder]:checked').val() == "Yes") {
      $('input[name=male_psychiatric_disorder_text]').show();
    } else if ($('input[name=male_psychiatric_disorder]:checked').val() == "No") {
      $('input[name=male_psychiatric_disorder_text]').hide();
    }
  });
  $('input[name=male_thyroid_disorder_text]').hide();
  if ($('input[name=male_thyroid_disorder]:checked').val() == "Yes") {
    $('input[name=male_thyroid_disorder_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_thyroid_disorder]:checked').val() == "Yes") {
      $('input[name=male_thyroid_disorder_text]').show();
    } else if ($('input[name=male_thyroid_disorder]:checked').val() == "No") {
      $('input[name=male_thyroid_disorder_text]').hide();
    }
  });
  $('input[name=male_urinary_infection_text]').hide();
  if ($('input[name=male_urinary_infection]:checked').val() == "Yes") {
    $('input[name=male_urinary_infection_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_urinary_infection]:checked').val() == "Yes") {
      $('input[name=male_urinary_infection_text]').show();
    } else if ($('input[name=male_urinary_infection]:checked').val() == "No") {
      $('input[name=male_urinary_infection_text]').hide();
    }
  });
  $('input[name=male_sexually_transmitted_text]').hide();
  if ($('input[name=male_sexually_transmitted]:checked').val() == "Yes") {
    $('input[name=male_sexually_transmitted_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_sexually_transmitted]:checked').val() == "Yes") {
      $('input[name=male_sexually_transmitted_text]').show();
    } else if ($('input[name=male_sexually_transmitted]:checked').val() == "No") {
      $('input[name=male_sexually_transmitted_text]').hide();
    }
  });
  $('input[name=sexually_transmitted_text]').hide();
  if ($('input[name=sexually_transmitted_disease]:checked').val() == "Yes") {
    $('input[name=sexually_transmitted_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=sexually_transmitted_disease]:checked').val() == "Yes") {
      $('input[name=sexually_transmitted_text]').show();
    } else if ($('input[name=sexually_transmitted_disease]:checked').val() == "No") {
      $('input[name=sexually_transmitted_text]').hide();
    }
  });
  $('input[name=abdominal_operations_text]').hide();
  if ($('input[name=abdominal_operations]:checked').val() == "Yes") {
    $('input[name=abdominal_operations_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=abdominal_operations]:checked').val() == "Yes") {
      $('input[name=abdominal_operations_text]').show();
    } else if ($('input[name=abdominal_operations]:checked').val() == "No") {
      $('input[name=abdominal_operations_text]').hide();
    }
  });
  $('input[name=other_operations_text]').hide();
  if ($('input[name=other_operations]:checked').val() == "Yes") {
    $('input[name=other_operations_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=other_operations]:checked').val() == "Yes") {
      $('input[name=other_operations_text]').show();
    } else if ($('input[name=other_operations]:checked').val() == "No") {
      $('input[name=other_operations_text]').hide();
    }
  });
  $('input[name=male_abdominal_operations_text]').hide();
  if ($('input[name=male_abdominal_operations]:checked').val() == "Yes") {
    $('input[name=male_abdominal_operations_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_abdominal_operations]:checked').val() == "Yes") {
      $('input[name=male_abdominal_operations_text]').show();
    } else if ($('input[name=male_abdominal_operations]:checked').val() == "No") {
      $('input[name=male_abdominal_operations_text]').hide();
    }
  });
  $('input[name=male_other_operations_text]').hide();
  if ($('input[name=male_other_operations]:checked').val() == "Yes") {
    $('input[name=male_other_operations_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_other_operations]:checked').val() == "Yes") {
      $('input[name=male_other_operations_text]').show();
    } else if ($('input[name=male_other_operations]:checked').val() == "No") {
      $('input[name=male_other_operations_text]').hide();
    }
  });
  $('input[name=medications_text]').hide();
  if ($('input[name=medications]:checked').val() == "Yes") {
    $('input[name=medications_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=medications]:checked').val() == "Yes") {
      $('input[name=medications_text]').show();
    } else if ($('input[name=medications]:checked').val() == "No") {
      $('input[name=medications_text]').hide();
    }
  });
  $('input[name=environmental_factors_text]').hide();
  if ($('input[name=environmental_factors]:checked').val() == "Yes") {
    $('input[name=environmental_factors_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=environmental_factors]:checked').val() == "Yes") {
      $('input[name=environmental_factors_text]').show();
    } else if ($('input[name=environmental_factors]:checked').val() == "No") {
      $('input[name=environmental_factors_text]').hide();
    }
  });
  $('input[name=male_medications_text]').hide();
  if ($('input[name=male_medications]:checked').val() == "Yes") {
    $('input[name=male_medications_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_medications]:checked').val() == "Yes") {
      $('input[name=male_medications_text]').show();
    } else if ($('input[name=male_medications]:checked').val() == "No") {
      $('input[name=male_medications_text]').hide();
    }
  });
  $('input[name=male_environmental_factors_text]').hide();
  if ($('input[name=male_environmental_factors]:checked').val() == "Yes") {
    $('input[name=male_environmental_factors_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_environmental_factors]:checked').val() == "Yes") {
      $('input[name=male_environmental_factors_text]').show();
    } else if ($('input[name=male_environmental_factors]:checked').val() == "No") {
      $('input[name=male_environmental_factors_text]').hide();
    }
  });
  $('input[name=dentures_text]').hide();
  if ($('input[name=dentures]:checked').val() == "Yes") {
    $('input[name=dentures_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=dentures]:checked').val() == "Yes") {
      $('input[name=dentures_text]').show();
    } else if ($('input[name=dentures]:checked').val() == "No") {
      $('input[name=dentures_text]').hide();
    }
  });
  $('input[name=loose_teeth_text]').hide();
  if ($('input[name=loose_teeth]:checked').val() == "Yes") {
    $('input[name=loose_teeth_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=loose_teeth]:checked').val() == "Yes") {
      $('input[name=loose_teeth_text]').show();
    } else if ($('input[name=loose_teeth]:checked').val() == "No") {
      $('input[name=loose_teeth_text]').hide();
    }
  });
  $('input[name=hearing_aid_text]').hide();
  if ($('input[name=hearing_aid]:checked').val() == "Yes") {
    $('input[name=hearing_aid_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=hearing_aid]:checked').val() == "Yes") {
      $('input[name=hearing_aid_text]').show();
    } else if ($('input[name=hearing_aid]:checked').val() == "No") {
      $('input[name=hearing_aid_text]').hide();
    }
  });
  $('input[name=caps_on_front_teeth_text]').hide();
  if ($('input[name=caps_on_front_teeth]:checked').val() == "Yes") {
    $('input[name=caps_on_front_teeth_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=caps_on_front_teeth]:checked').val() == "Yes") {
      $('input[name=caps_on_front_teeth_text]').show();
    } else if ($('input[name=caps_on_front_teeth]:checked').val() == "No") {
      $('input[name=caps_on_front_teeth_text]').hide();
    }
  });
  $('input[name=contact_lenses_text]').hide();
  if ($('input[name=contact_lenses]:checked').val() == "Yes") {
    $('input[name=contact_lenses_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=contact_lenses]:checked').val() == "Yes") {
      $('input[name=contact_lenses_text]').show();
    } else if ($('input[name=contact_lenses]:checked').val() == "No") {
      $('input[name=contact_lenses_text]').hide();
    }
  });
  $('input[name=contact_lenses_text]').hide();
  if ($('input[name=conatact_lenses]:checked').val() == "Yes") {
    $('input[name=contact_lenses_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=conatact_lenses]:checked').val() == "Yes") {
      $('input[name=contact_lenses_text]').show();
    } else if ($('input[name=conatact_lenses]:checked').val() == "No") {
      $('input[name=contact_lenses_text]').hide();
    }
  });
  $('input[name=body_piercing_text]').hide();
  if ($('input[name=body_piercing]:checked').val() == "Yes") {
    $('input[name=body_piercing_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=body_piercing]:checked').val() == "Yes") {
      $('input[name=body_piercing_text]').show();
    } else if ($('input[name=body_piercing]:checked').val() == "No") {
      $('input[name=body_piercing_text]').hide();
    }
  });
  $('input[name=blood_transfusion_text]').hide();
  if ($('input[name=blood_transfusion]:checked').val() == "Yes") {
    $('input[name=blood_transfusion_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=blood_transfusion]:checked').val() == "Yes") {
      $('input[name=blood_transfusion_text]').show();
    } else if ($('input[name=blood_transfusion]:checked').val() == "No") {
      $('input[name=blood_transfusion_text]').hide();
    }
  });
  $('input[name=traffic_accident_text]').hide();
  if ($('input[name=traffic_accident]:checked').val() == "Yes") {
    $('input[name=traffic_accident_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=traffic_accident]:checked').val() == "Yes") {
      $('input[name=traffic_accident_text]').show();
    } else if ($('input[name=traffic_accident]:checked').val() == "No") {
      $('input[name=traffic_accident_text]').hide();
    }
  });
  $('input[name=smoke_past_text]').hide();
  if ($('input[name=smoke_past]:checked').val() == "Yes") {
    $('input[name=smoke_past_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=smoke_past]:checked').val() == "Yes") {
      $('input[name=smoke_past_text]').show();
    } else if ($('input[name=smoke_past]:checked').val() == "No") {
      $('input[name=smoke_past_text]').hide();
    }
  });
  $('input[name=smoke_present_text]').hide();
  if ($('input[name=smoke_present]:checked').val() == "Yes") {
    $('input[name=smoke_present_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=smoke_present]:checked').val() == "Yes") {
      $('input[name=smoke_present_text]').show();
    } else if ($('input[name=smoke_present]:checked').val() == "No") {
      $('input[name=smoke_present_text]').hide();
    }
  });
  $('input[name=drink_past_text]').hide();
  if ($('input[name=drink_past]:checked').val() == "Yes") {
    $('input[name=drink_past_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=drink_past]:checked').val() == "Yes") {
      $('input[name=drink_past_text]').show();
    } else if ($('input[name=drink_past]:checked').val() == "No") {
      $('input[name=drink_past_text]').hide();
    }
  });
  $('input[name=drink_present_text]').hide();
  if ($('input[name=drink_present]:checked').val() == "Yes") {
    $('input[name=drink_present_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=drink_present]:checked').val() == "Yes") {
      $('input[name=drink_present_text]').show();
    } else if ($('input[name=drink_present]:checked').val() == "No") {
      $('input[name=drink_present_text]').hide();
    }
  });
  $('input[name=drink_present_text]').hide();
  if ($('input[name=drnk_present]:checked').val() == "Yes") {
    $('input[name=drink_present_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=drnk_present]:checked').val() == "Yes") {
      $('input[name=drink_present_text]').show();
    } else if ($('input[name=drnk_present]:checked').val() == "No") {
      $('input[name=drink_present_text]').hide();
    }
  });
  $('input[name=abusive_drugs_text]').hide();
  if ($('input[name=abusive_drugs]:checked').val() == "Yes") {
    $('input[name=abusive_drugs_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=abusive_drugs]:checked').val() == "Yes") {
      $('input[name=abusive_drugs_text]').show();
    } else if ($('input[name=abusive_drugs]:checked').val() == "No") {
      $('input[name=abusive_drugs_text]').hide();
    }
  });
  $('input[name=drugs_text]').hide();
  if ($('input[name=drugs]:checked').val() == "Yes") {
    $('input[name=drugs_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=drugs]:checked').val() == "Yes") {
      $('input[name=drugs_text]').show();
    } else if ($('input[name=drugs]:checked').val() == "No") {
      $('input[name=drugs_text]').hide();
    }
  });
  $('input[name=steroid_text]').hide();
  if ($('input[name=steroid]:checked').val() == "Yes") {
    $('input[name=steroid_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=steroid]:checked').val() == "Yes") {
      $('input[name=steroid_text]').show();
    } else if ($('input[name=steroid]:checked').val() == "No") {
      $('input[name=steroid_text]').hide();
    }
  });
  $('input[name=medication_text]').hide();
  if ($('input[name=medication]:checked').val() == "Yes") {
    $('input[name=medication_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=medication]:checked').val() == "Yes") {
      $('input[name=medication_text]').show();
    } else if ($('input[name=medication]:checked').val() == "No") {
      $('input[name=medication_text]').hide();
    }
  });
  $('input[name=herbal_products_text]').hide();
  if ($('input[name=herbal_products]:checked').val() == "Yes") {
    $('input[name=herbal_products_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=herbal_products]:checked').val() == "Yes") {
      $('input[name=herbal_products_text]').show();
    } else if ($('input[name=herbal_products]:checked').val() == "No") {
      $('input[name=herbal_products_text]').hide();
    }
  });
  $('input[name=eye_drops_text]').hide();
  if ($('input[name=eye_drops]:checked').val() == "Yes") {
    $('input[name=eye_drops_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=eye_drops]:checked').val() == "Yes") {
      $('input[name=eye_drops_text]').show();
    } else if ($('input[name=eye_drops]:checked').val() == "No") {
      $('input[name=eye_drops_text]').hide();
    }
  });
  $('input[name=non_prescription_drugs_text]').hide();
  if ($('input[name=non_prescription_drugs]:checked').val() == "Yes") {
    $('input[name=non_prescription_drugs_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=non_prescription_drugs]:checked').val() == "Yes") {
      $('input[name=non_prescription_drugs_text]').show();
    } else if ($('input[name=non_prescription_drugs]:checked').val() == "No") {
      $('input[name=non_prescription_drugs_text]').hide();
    }
  });
  $('input[name=male_dentures_text]').hide();
  if ($('input[name=male_dentures]:checked').val() == "Yes") {
    $('input[name=male_dentures_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_dentures]:checked').val() == "Yes") {
      $('input[name=male_dentures_text]').show();
    } else if ($('input[name=male_dentures]:checked').val() == "No") {
      $('input[name=male_dentures_text]').hide();
    }
  });
  $('input[name=male_loose_teeth_text]').hide();
  if ($('input[name=male_loose_teeth]:checked').val() == "Yes") {
    $('input[name=male_loose_teeth_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_loose_teeth]:checked').val() == "Yes") {
      $('input[name=male_loose_teeth_text]').show();
    } else if ($('input[name=male_loose_teeth]:checked').val() == "No") {
      $('input[name=male_loose_teeth_text]').hide();
    }
  });
  $('input[name=male_hearing_aid_text]').hide();
  if ($('input[name=male_hearing_aid]:checked').val() == "Yes") {
    $('input[name=male_hearing_aid_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_hearing_aid]:checked').val() == "Yes") {
      $('input[name=male_hearing_aid_text]').show();
    } else if ($('input[name=male_hearing_aid]:checked').val() == "No") {
      $('input[name=male_hearing_aid_text]').hide();
    }
  });
  $('input[name=male_caps_on_front_teeth_text]').hide();
  if ($('input[name=male_caps_on_front_teeth]:checked').val() == "Yes") {
    $('input[name=male_caps_on_front_teeth_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_caps_on_front_teeth]:checked').val() == "Yes") {
      $('input[name=male_caps_on_front_teeth_text]').show();
    } else if ($('input[name=male_caps_on_front_teeth]:checked').val() == "No") {
      $('input[name=male_caps_on_front_teeth_text]').hide();
    }
  });
  $('input[name=male_contact_lenses_text]').hide();
  if ($('input[name=male_contact_lenses]:checked').val() == "Yes") {
    $('input[name=male_contact_lenses_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_contact_lenses]:checked').val() == "Yes") {
      $('input[name=male_contact_lenses_text]').show();
    } else if ($('input[name=male_contact_lenses]:checked').val() == "No") {
      $('input[name=male_contact_lenses_text]').hide();
    }
  });
  $('input[name=male_contact_lenses_text]').hide();
  if ($('input[name=male_conatact_lenses]:checked').val() == "Yes") {
    $('input[name=male_contact_lenses_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_conatact_lenses]:checked').val() == "Yes") {
      $('input[name=male_contact_lenses_text]').show();
    } else if ($('input[name=male_conatact_lenses]:checked').val() == "No") {
      $('input[name=male_contact_lenses_text]').hide();
    }
  });
  $('input[name=male_body_piercing_text]').hide();
  if ($('input[name=male_body_piercing]:checked').val() == "Yes") {
    $('input[name=male_body_piercing_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_body_piercing]:checked').val() == "Yes") {
      $('input[name=male_body_piercing_text]').show();
    } else if ($('input[name=male_body_piercing]:checked').val() == "No") {
      $('input[name=male_body_piercing_text]').hide();
    }
  });
  $('input[name=male_blood_transfusion_text]').hide();
  if ($('input[name=male_blood_transfusion]:checked').val() == "Yes") {
    $('input[name=male_blood_transfusion_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_blood_transfusion]:checked').val() == "Yes") {
      $('input[name=male_blood_transfusion_text]').show();
    } else if ($('input[name=male_blood_transfusion]:checked').val() == "No") {
      $('input[name=male_blood_transfusion_text]').hide();
    }
  });
  $('input[name=male_traffic_accident_text]').hide();
  if ($('input[name=male_traffic_accident]:checked').val() == "Yes") {
    $('input[name=male_traffic_accident_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_traffic_accident]:checked').val() == "Yes") {
      $('input[name=male_traffic_accident_text]').show();
    } else if ($('input[name=male_traffic_accident]:checked').val() == "No") {
      $('input[name=male_traffic_accident_text]').hide();
    }
  });
  $('input[name=male_smoke_past_text]').hide();
  if ($('input[name=male_smoke_past]:checked').val() == "Yes") {
    $('input[name=male_smoke_past_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_smoke_past]:checked').val() == "Yes") {
      $('input[name=male_smoke_past_text]').show();
    } else if ($('input[name=male_smoke_past]:checked').val() == "No") {
      $('input[name=male_smoke_past_text]').hide();
    }
  });
  $('input[name=male_smoke_present_text]').hide();
  if ($('input[name=male_smoke_present]:checked').val() == "Yes") {
    $('input[name=male_smoke_present_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_smoke_present]:checked').val() == "Yes") {
      $('input[name=male_smoke_present_text]').show();
    } else if ($('input[name=male_smoke_present]:checked').val() == "No") {
      $('input[name=male_smoke_present_text]').hide();
    }
  });
  $('input[name=male_drink_past_text]').hide();
  if ($('input[name=male_drink_past]:checked').val() == "Yes") {
    $('input[name=male_drink_past_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_drink_past]:checked').val() == "Yes") {
      $('input[name=male_drink_past_text]').show();
    } else if ($('input[name=male_drink_past]:checked').val() == "No") {
      $('input[name=male_drink_past_text]').hide();
    }
  });
  $('input[name=male_drink_present_text]').hide();
  if ($('input[name=male_drink_present]:checked').val() == "Yes") {
    $('input[name=male_drink_present_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_drink_present]:checked').val() == "Yes") {
      $('input[name=male_drink_present_text]').show();
    } else if ($('input[name=male_drink_present]:checked').val() == "No") {
      $('input[name=male_drink_present_text]').hide();
    }
  });
  $('input[name=male_drink_present_text]').hide();
  if ($('input[name=male_drnk_present]:checked').val() == "Yes") {
    $('input[name=male_drink_present_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_drnk_present]:checked').val() == "Yes") {
      $('input[name=male_drink_present_text]').show();
    } else if ($('input[name=male_drnk_present]:checked').val() == "No") {
      $('input[name=male_drink_present_text]').hide();
    }
  });
  $('input[name=male_abusive_drugs_text]').hide();
  if ($('input[name=male_abusive_drugs]:checked').val() == "Yes") {
    $('input[name=male_abusive_drugs_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_abusive_drugs]:checked').val() == "Yes") {
      $('input[name=male_abusive_drugs_text]').show();
    } else if ($('input[name=male_abusive_drugs]:checked').val() == "No") {
      $('input[name=male_abusive_drugs_text]').hide();
    }
  });
  $('input[name=male_drugs_text]').hide();
  if ($('input[name=male_drugs]:checked').val() == "Yes") {
    $('input[name=male_drugs_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_drugs]:checked').val() == "Yes") {
      $('input[name=male_drugs_text]').show();
    } else if ($('input[name=male_drugs]:checked').val() == "No") {
      $('input[name=male_drugs_text]').hide();
    }
  });
  $('input[name=male_steroid_text]').hide();
  if ($('input[name=male_steroid]:checked').val() == "Yes") {
    $('input[name=male_steroid_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_steroid]:checked').val() == "Yes") {
      $('input[name=male_steroid_text]').show();
    } else if ($('input[name=male_steroid]:checked').val() == "No") {
      $('input[name=male_steroid_text]').hide();
    }
  });
  $('input[name=male_medication_text]').hide();
  if ($('input[name=male_medication]:checked').val() == "Yes") {
    $('input[name=male_medication_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_medication]:checked').val() == "Yes") {
      $('input[name=male_medication_text]').show();
    } else if ($('input[name=male_medication]:checked').val() == "No") {
      $('input[name=male_medication_text]').hide();
    }
  });
  $('input[name=male_herbal_products_text]').hide();
  if ($('input[name=male_herbal_products]:checked').val() == "Yes") {
    $('input[name=male_herbal_products_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_herbal_products]:checked').val() == "Yes") {
      $('input[name=male_herbal_products_text]').show();
    } else if ($('input[name=male_herbal_products]:checked').val() == "No") {
      $('input[name=male_herbal_products_text]').hide();
    }
  });
  $('input[name=male_eye_drops_text]').hide();
  if ($('input[name=male_eye_drops]:checked').val() == "Yes") {
    $('input[name=male_eye_drops_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_eye_drops]:checked').val() == "Yes") {
      $('input[name=male_eye_drops_text]').show();
    } else if ($('input[name=male_eye_drops]:checked').val() == "No") {
      $('input[name=male_eye_drops_text]').hide();
    }
  });
  $('input[name=male_non_prescription_drugs_text]').hide();
  if ($('input[name=male_non_prescription_drugs]:checked').val() == "Yes") {
    $('input[name=male_non_prescription_drugs_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=male_non_prescription_drugs]:checked').val() == "Yes") {
      $('input[name=male_non_prescription_drugs_text]').show();
    } else if ($('input[name=male_non_prescription_drugs]:checked').val() == "No") {
      $('input[name=male_non_prescription_drugs_text]').hide();
    }
  });
  $('input[name=maternal_diabetes_text]').hide();
  if ($('input[name=maternal_diabetes]:checked').val() == "Yes") {
    $('input[name=maternal_diabetes_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=maternal_diabetes]:checked').val() == "Yes") {
      $('input[name=maternal_diabetes_text]').show();
    } else if ($('input[name=maternal_diabetes]:checked').val() == "No") {
      $('input[name=maternal_diabetes_text]').hide();
    }
  });
  $('input[name=paternal_diabetes_text]').hide();
  if ($('input[name=paternal_diabetes]:checked').val() == "Yes") {
    $('input[name=paternal_diabetes_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=paternal_diabetes]:checked').val() == "Yes") {
      $('input[name=paternal_diabetes_text]').show();
    } else if ($('input[name=paternal_diabetes]:checked').val() == "No") {
      $('input[name=paternal_diabetes_text]').hide();
    }
  });
  $('input[name=maternal_thrombo_embolism_text]').hide();
  if ($('input[name=maternal_thrombo_embolism]:checked').val() == "Yes") {
    $('input[name=maternal_thrombo_embolism_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=maternal_thrombo_embolism]:checked').val() == "Yes") {
      $('input[name=maternal_thrombo_embolism_text]').show();
    } else if ($('input[name=maternal_thrombo_embolism]:checked').val() == "No") {
      $('input[name=maternal_thrombo_embolism_text]').hide();
    }
  });
  $('input[name=paternal_thrombo_embolism_text]').hide();
  if ($('input[name=paternal_thrombo_embolism]:checked').val() == "Yes") {
    $('input[name=paternal_thrombo_embolism_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=paternal_thrombo_embolism]:checked').val() == "Yes") {
      $('input[name=paternal_thrombo_embolism_text]').show();
    } else if ($('input[name=paternal_thrombo_embolism]:checked').val() == "No") {
      $('input[name=paternal_thrombo_embolism_text]').hide();
    }
  });
  $('input[name=maternal_metabolic_text]').hide();
  if ($('input[name=maternal_metabolic]:checked').val() == "Yes") {
    $('input[name=maternal_metabolic_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=maternal_metabolic]:checked').val() == "Yes") {
      $('input[name=maternal_metabolic_text]').show();
    } else if ($('input[name=maternal_metabolic]:checked').val() == "No") {
      $('input[name=maternal_metabolic_text]').hide();
    }
  });
  $('input[name=paternal_metabolic_text]').hide();
  if ($('input[name=paternal_metabolic]:checked').val() == "Yes") {
    $('input[name=paternal_metabolic_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=paternal_metabolic]:checked').val() == "Yes") {
      $('input[name=paternal_metabolic_text]').show();
    } else if ($('input[name=paternal_metabolic]:checked').val() == "No") {
      $('input[name=paternal_metabolic_text]').hide();
    }
  });
  $('input[name=maternal_urinary_tract_text]').hide();
  if ($('input[name=maternal_urinary_tract]:checked').val() == "Yes") {
    $('input[name=maternal_urinary_tract_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=maternal_urinary_tract]:checked').val() == "Yes") {
      $('input[name=maternal_urinary_tract_text]').show();
    } else if ($('input[name=maternal_urinary_tract]:checked').val() == "No") {
      $('input[name=maternal_urinary_tract_text]').hide();
    }
  });
  $('input[name=paternal_urinary_tract_text]').hide();
  if ($('input[name=paternal_urinary_tract]:checked').val() == "Yes") {
    $('input[name=paternal_urinary_tract_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=paternal_urinary_tract]:checked').val() == "Yes") {
      $('input[name=paternal_urinary_tract_text]').show();
    } else if ($('input[name=paternal_urinary_tract]:checked').val() == "No") {
      $('input[name=paternal_urinary_tract_text]').hide();
    }
  });
  $('input[name=maternal_neurological_text]').hide();
  if ($('input[name=maternal_neurological]:checked').val() == "Yes") {
    $('input[name=maternal_neurological_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=maternal_neurological]:checked').val() == "Yes") {
      $('input[name=maternal_neurological_text]').show();
    } else if ($('input[name=maternal_neurological]:checked').val() == "No") {
      $('input[name=maternal_neurological_text]').hide();
    }
  });
  $('input[name=paternal_neurological_text]').hide();
  if ($('input[name=paternal_neurological]:checked').val() == "Yes") {
    $('input[name=paternal_neurological_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=paternal_neurological]:checked').val() == "Yes") {
      $('input[name=paternal_neurological_text]').show();
    } else if ($('input[name=paternal_neurological]:checked').val() == "No") {
      $('input[name=paternal_neurological_text]').hide();
    }
  });
  $('input[name=maternal_malignancy_text]').hide();
  if ($('input[name=maternal_malignancy]:checked').val() == "Yes") {
    $('input[name=maternal_malignancy_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=maternal_malignancy]:checked').val() == "Yes") {
      $('input[name=maternal_malignancy_text]').show();
    } else if ($('input[name=maternal_malignancy]:checked').val() == "No") {
      $('input[name=maternal_malignancy_text]').hide();
    }
  });
  $('input[name=paternal_malignancy_text]').hide();
  if ($('input[name=paternal_malignancy]:checked').val() == "Yes") {
    $('input[name=paternal_malignancy_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=paternal_malignancy]:checked').val() == "Yes") {
      $('input[name=paternal_malignancy_text]').show();
    } else if ($('input[name=paternal_malignancy]:checked').val() == "No") {
      $('input[name=paternal_malignancy_text]').hide();
    }
  });
  $('input[name=member_with_anesthesia_text]').hide();
  if ($('input[name=member_with_anesthesia]:checked').val() == "Yes") {
    $('input[name=member_with_anesthesia_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=member_with_anesthesia]:checked').val() == "Yes") {
      $('input[name=member_with_anesthesia_text]').show();
    } else if ($('input[name=member_with_anesthesia]:checked').val() == "No") {
      $('input[name=member_with_anesthesia_text]').hide();
    }
  });
  $('input[name=male_member_with_anesthesia_text]').hide();
  if ($('input[name=male_member_with_anesthesia]:checked').val() == "Yes") {
    $('input[name=male_member_with_anesthesia_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_member_with_anesthesia]:checked').val() == "Yes") {
      $('input[name=male_member_with_anesthesia_text]').show();
    } else if ($('input[name=male_member_with_anesthesia]:checked').val() == "No") {
      $('input[name=male_member_with_anesthesia_text]').hide();
    }
  });
  $('input[name=male_maternal_diabetes_text]').hide();
  if ($('input[name=male_maternal_diabetes]:checked').val() == "Yes") {
    $('input[name=male_maternal_diabetes_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_maternal_diabetes]:checked').val() == "Yes") {
      $('input[name=male_maternal_diabetes_text]').show();
    } else if ($('input[name=male_maternal_diabetes]:checked').val() == "No") {
      $('input[name=male_maternal_diabetes_text]').hide();
    }
  });
  $('input[name=male_paternal_diabetes_text]').hide();
  if ($('input[name=male_paternal_diabetes]:checked').val() == "Yes") {
    $('input[name=male_paternal_diabetes_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_paternal_diabetes]:checked').val() == "Yes") {
      $('input[name=male_paternal_diabetes_text]').show();
    } else if ($('input[name=male_paternal_diabetes]:checked').val() == "No") {
      $('input[name=male_paternal_diabetes_text]').hide();
    }
  });
  $('input[name=male_maternal_thrombo_embolism_text]').hide();
  if ($('input[name=male_maternal_thrombo_embolism]:checked').val() == "Yes") {
    $('input[name=male_maternal_thrombo_embolism_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_maternal_thrombo_embolism]:checked').val() == "Yes") {
      $('input[name=male_maternal_thrombo_embolism_text]').show();
    } else if ($('input[name=male_maternal_thrombo_embolism]:checked').val() == "No") {
      $('input[name=male_maternal_thrombo_embolism_text]').hide();
    }
  });
  $('input[name=male_paternal_thrombo_embolism_text]').hide();
  if ($('input[name=male_paternal_thrombo_embolism]:checked').val() == "Yes") {
    $('input[name=male_paternal_thrombo_embolism_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_paternal_thrombo_embolism]:checked').val() == "Yes") {
      $('input[name=male_paternal_thrombo_embolism_text]').show();
    } else if ($('input[name=male_paternal_thrombo_embolism]:checked').val() == "No") {
      $('input[name=male_paternal_thrombo_embolism_text]').hide();
    }
  });
  $('input[name=male_maternal_metabolic_text]').hide();
  if ($('input[name=male_maternal_metabolic]:checked').val() == "Yes") {
    $('input[name=male_maternal_metabolic_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_maternal_metabolic]:checked').val() == "Yes") {
      $('input[name=male_maternal_metabolic_text]').show();
    } else if ($('input[name=male_maternal_metabolic]:checked').val() == "No") {
      $('input[name=male_maternal_metabolic_text]').hide();
    }
  });
  $('input[name=male_paternal_metabolic_text]').hide();
  if ($('input[name=male_paternal_metabolic]:checked').val() == "Yes") {
    $('input[name=male_paternal_metabolic_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_paternal_metabolic]:checked').val() == "Yes") {
      $('input[name=male_paternal_metabolic_text]').show();
    } else if ($('input[name=male_paternal_metabolic]:checked').val() == "No") {
      $('input[name=male_paternal_metabolic_text]').hide();
    }
  });
  $('input[name=male_maternal_urinary_tract_text]').hide();
  if ($('input[name=male_maternal_urinary_tract]:checked').val() == "Yes") {
    $('input[name=male_maternal_urinary_tract_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_maternal_urinary_tract]:checked').val() == "Yes") {
      $('input[name=male_maternal_urinary_tract_text]').show();
    } else if ($('input[name=male_maternal_urinary_tract]:checked').val() == "No") {
      $('input[name=male_maternal_urinary_tract_text]').hide();
    }
  });
  $('input[name=male_paternal_urinary_tract_text]').hide();
  if ($('input[name=male_paternal_urinary_tract]:checked').val() == "Yes") {
    $('input[name=male_paternal_urinary_tract_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_paternal_urinary_tract]:checked').val() == "Yes") {
      $('input[name=male_paternal_urinary_tract_text]').show();
    } else if ($('input[name=male_paternal_urinary_tract]:checked').val() == "No") {
      $('input[name=male_paternal_urinary_tract_text]').hide();
    }
  });
  $('input[name=male_maternal_neurological_text]').hide();
  if ($('input[name=male_maternal_neurological]:checked').val() == "Yes") {
    $('input[name=male_maternal_neurological_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_maternal_neurological]:checked').val() == "Yes") {
      $('input[name=male_maternal_neurological_text]').show();
    } else if ($('input[name=male_maternal_neurological]:checked').val() == "No") {
      $('input[name=male_maternal_neurological_text]').hide();
    }
  });
  $('input[name=male_paternal_neurological_text]').hide();
  if ($('input[name=male_paternal_neurological]:checked').val() == "Yes") {
    $('input[name=male_paternal_neurological_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_paternal_neurological]:checked').val() == "Yes") {
      $('input[name=male_paternal_neurological_text]').show();
    } else if ($('input[name=male_paternal_neurological]:checked').val() == "No") {
      $('input[name=male_paternal_neurological_text]').hide();
    }
  });
  $('input[name=male_maternal_malignancy_text]').hide();
  if ($('input[name=male_maternal_malignancy]:checked').val() == "Yes") {
    $('input[name=male_maternal_malignancy_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_maternal_malignancy]:checked').val() == "Yes") {
      $('input[name=male_maternal_malignancy_text]').show();
    } else if ($('input[name=male_maternal_malignancy]:checked').val() == "No") {
      $('input[name=male_maternal_malignancy_text]').hide();
    }
  });
  $('input[name=male_paternal_malignancy_text]').hide();
  if ($('input[name=male_paternal_malignancy]:checked').val() == "Yes") {
    $('input[name=male_paternal_malignancy_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_paternal_malignancy]:checked').val() == "Yes") {
      $('input[name=male_paternal_malignancy_text]').show();
    } else if ($('input[name=male_paternal_malignancy]:checked').val() == "No") {
      $('input[name=male_paternal_malignancy_text]').hide();
    }
  });
  $('input[name=female_history_of_abnormality_text]').hide();
  if ($('input[name=female_history_of_abnormality]:checked').val() == "Yes") {
    $('input[name=female_history_of_abnormality_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=female_history_of_abnormality]:checked').val() == "Yes") {
      $('input[name=female_history_of_abnormality_text]').show();
    } else if ($('input[name=female_history_of_abnormality]:checked').val() == "No") {
      $('input[name=female_history_of_abnormality_text]').hide();
    }
  });
  $('input[name=male_history_of_abnormality_text]').hide();
  if ($('input[name=male_history_of_abnormality]:checked').val() == "Yes") {
    $('input[name=male_history_of_abnormality_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=male_history_of_abnormality]:checked').val() == "Yes") {
      $('input[name=male_history_of_abnormality_text]').show();
    } else if ($('input[name=male_history_of_abnormality]:checked').val() == "No") {
      $('input[name=male_history_of_abnormality_text]').hide();
    }
  });
  $('input[name=operations_text]').hide();
  if ($('input[name=operations]:checked').val() == "Yes") {
    $('input[name=operations_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=operations]:checked').val() == "Yes") {
      $('input[name=operations_text]').show();
    } else if ($('input[name=operations]:checked').val() == "No") {
      $('input[name=operations_text]').hide();
    }
  });
  $('input[name=nonprescription_drugs_text]').hide();
  if ($('input[name=nonprescription_drugs]:checked').val() == "Yes") {
    $('input[name=nonprescription_drugs_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=nonprescription_drugs]:checked').val() == "Yes") {
      $('input[name=nonprescription_drugs_text]').show();
    } else if ($('input[name=nonprescription_drugs]:checked').val() == "No") {
      $('input[name=nonprescription_drugs_text]').hide();
    }
  });
  $('input[name=children_age]').hide();
  if ($('input[name=children]:checked').val() == "Yes") {
    $('input[name=children_age]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=children]:checked').val() == "Yes") {
      $('input[name=children_age]').show();
    } else if ($('input[name=children]:checked').val() == "No") {
      $('input[name=children_age]').hide();
    }
  });
  $('input[name=therapy_before_text]').hide();
  if ($('input[name=therapy_before]:checked').val() == "Yes") {
    $('input[name=therapy_before_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=therapy_before]:checked').val() == "Yes") {
      $('input[name=therapy_before_text]').show();
    } else if ($('input[name=therapy_before]:checked').val() == "No") {
      $('input[name=therapy_before_text]').hide();
    }
  });
  $('input[name=hospitalized_text]').hide();
  if ($('input[name=hospitalized]:checked').val() == "Yes") {
    $('input[name=hospitalized_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=hospitalized]:checked').val() == "Yes") {
      $('input[name=hospitalized_text]').show();
    } else if ($('input[name=hospitalized]:checked').val() == "No") {
      $('input[name=hospitalized_text]').hide();
    }
  });
  $('input[name=iui_cycle_times_text]').hide();
  if ($('input[name=iui_cycle_times]:checked').val() == "Yes") {
    $('input[name=iui_cycle_times_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=iui_cycle_times]:checked').val() == "Yes") {
      $('input[name=iui_cycle_times_text]').show();
    } else if ($('input[name=iui_cycle_times]:checked').val() == "No") {
      $('input[name=iui_cycle_times_text]').hide();
    }
  });
  $('input[name=ivf_cycle_times_text]').hide();
  if ($('input[name=ivf_cycle_times]:checked').val() == "Yes") {
    $('input[name=ivf_cycle_times_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=ivf_cycle_times]:checked').val() == "Yes") {
      $('input[name=ivf_cycle_times_text]').show();
    } else if ($('input[name=ivf_cycle_times]:checked').val() == "No") {
      $('input[name=ivf_cycle_times_text]').hide();
    }
  });
  $('input[name=sexual_abuse_text]').hide();
  if ($('input[name=sexual_abuse]:checked').val() == "Yes") {
    $('input[name=sexual_abuse_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=sexual_abuse]:checked').val() == "Yes") {
      $('input[name=sexual_abuse_text]').show();
    } else if ($('input[name=sexual_abuse]:checked').val() == "No") {
      $('input[name=sexual_abuse_text]').hide();
    }
  });
  $('input[name=legal_problem_text]').hide();
  if ($('input[name=legal_problem]:checked').val() == "Yes") {
    $('input[name=legal_problem_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=legal_problem]:checked').val() == "Yes") {
      $('input[name=legal_problem_text]').show();
    } else if ($('input[name=legal_problem]:checked').val() == "No") {
      $('input[name=legal_problem_text]').hide();
    }
  });
  $('input[name=cvs_text]').hide();
  if ($('input[name=cvs]:checked').val() == "Yes") {
    $('input[name=cvs_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=cvs]:checked').val() == "Yes") {
      $('input[name=cvs_text]').show();
    } else if ($('input[name=cvs]:checked').val() == "No") {
      $('input[name=cvs_text]').hide();
    }
  });
  $('input[name=chest_text]').hide();
  if ($('input[name=chest]:checked').val() == "Yes") {
    $('input[name=chest_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=chest]:checked').val() == "Yes") {
      $('input[name=chest_text]').show();
    } else if ($('input[name=chest]:checked').val() == "No") {
      $('input[name=chest_text]').hide();
    }
  });
  $('input[name=abdomen_text]').hide();
  if ($('input[name=abdomen]:checked').val() == "No") {
    $('input[name=abdomen_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=abdomen]:checked').val() == "No") {
      $('input[name=abdomen_text]').show();
    } else if ($('input[name=abdomen]:checked').val() == "Yes") {
      $('input[name=abdomen_text]').hide();
    }
  });
  $('input[name=psychological_problem_text]').hide();
  if ($('input[name=psychological_problem]:checked').val() == "Yes") {
    $('input[name=psychological_problem_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=psychological_problem]:checked').val() == "Yes") {
      $('input[name=psychological_problem_text]').show();
    } else if ($('input[name=psychological_problem]:checked').val() == "No") {
      $('input[name=psychological_problem_text]').hide();
    }
  });
  $('input[name=suicide_attempt_text]').hide();
  if ($('input[name=suicide_attempt]:checked').val() == "Yes") {
    $('input[name=suicide_attempt_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=suicide_attempt]:checked').val() == "Yes") {
      $('input[name=suicide_attempt_text]').show();
    } else if ($('input[name=suicide_attempt]:checked').val() == "No") {
      $('input[name=suicide_attempt_text]').hide();
    }
  });
  $('input[name=sexual_abuse_text]').hide();
  if ($('input[name=sexual_abuse]:checked').val() == "Yes") {
    $('input[name=sexual_abuse_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=sexual_abuse]:checked').val() == "Yes") {
      $('input[name=sexual_abuse_text]').show();
    } else if ($('input[name=sexual_abuse]:checked').val() == "No") {
      $('input[name=sexual_abuse_text]').hide();
    }
  });
  $('input[name=egg_cycles_before_text]').hide();
  if ($('input[name=egg_cycles_before]:checked').val() == "Yes") {
    $('input[name=egg_cycles_before_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=egg_cycles_before]:checked').val() == "Yes") {
      $('input[name=egg_cycles_before_text]').show();
    } else if ($('input[name=egg_cycles_before]:checked').val() == "No") {
      $('input[name=egg_cycles_before_text]').hide();
    }
  });
  $('input[name=surrogacy_cycles_before_text]').hide();
  if ($('input[name=surrogacy_cycles_before]:checked').val() == "Yes") {
    $('input[name=surrogacy_cycles_before_text]').show();
  }
  
    $('input[name=female_local_exam_pap_text]').hide();
    $("input[name='female_local_exam_pap_text']").change(function(){
        alert($(this).val());
       if($(this).val()=="Yes")
       {
          $('input#female_local_exam_pap_text').show();
       }
       else
       {
           $('input#female_local_exam_pap_text').hide();
       }
    });
    
    $('input[name=female_hvs_taken_text]').hide();
    $("input[name='female_hvs_taken_text']").change(function(){
       if($(this).val()=="Yes")
       {
          $('input[name=female_hvs_taken_text]').show();
       }
       else
       {
           $('input[name=female_hvs_taken_text]').hide();
       }
    });
    
    $('input[name=female_endometrial_biopsy_text]').hide();
    $("input[name='female_endometrial_biopsy_text']").change(function(){
       if($(this).val()=="Yes")
       {
          $('input[name=female_endometrial_biopsy_text]').show();
       }
       else
       {
           $('input[name=female_endometrial_biopsy_text]').hide();
       }
    });
  
  
  $("input:radio").click(function () {
    if ($('input[name=surrogacy_cycles_before]:checked').val() == "Yes") {
      $('input[name=surrogacy_cycles_before_text]').show();
    } else if ($('input[name=surrogacy_cycles_before]:checked').val() == "No") {
      $('input[name=surrogacy_cycles_before_text]').hide();
    }
  });
});