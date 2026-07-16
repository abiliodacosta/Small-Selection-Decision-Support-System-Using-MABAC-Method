import os
import glob

base_dir = 'c:/xampp/htdocs/app-dss/app/Controllers'

renames = {
    'CandidateController.php': 'CandidatesController.php',
    'CriterionController.php': 'CriteriaController.php',
    'EvaluationController.php': 'EvaluationsController.php',
    'ReportController.php': 'ReportsController.php'
}

for old, new in renames.items():
    old_path = os.path.join(base_dir, old)
    new_path = os.path.join(base_dir, new)
    if os.path.exists(old_path):
        with open(old_path, 'r', encoding='utf-8') as f:
            content = f.read()
        
        # Replace class names
        content = content.replace('class CandidateController', 'class CandidatesController')
        content = content.replace('class CriterionController', 'class CriteriaController')
        content = content.replace('class EvaluationController', 'class EvaluationsController')
        content = content.replace('class ReportController', 'class ReportsController')
        
        with open(new_path, 'w', encoding='utf-8') as f:
            f.write(content)
        
        os.remove(old_path)
        print(f"Renamed {old} to {new} and updated class name.")
