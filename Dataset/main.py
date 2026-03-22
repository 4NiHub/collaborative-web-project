from database import load_v2_data
from analytics import (
    calculate_weighted_gpa,
    calculate_student_ratings,
    export_analyst_results,
    get_improvement_projections
)

def run_analysis_pipeline(student_id):
    print(f"--- STARTING DATA ANALYSIS FOR STUDENT ID: {student_id} ---")

    # 1. Update the JSON for the HTML Dashboard
    # This fulfills all your web requirements at once
    export_analyst_results(student_id)

    # 2. Terminal View for the Analyst (Debugging)
    print("\n[Calculated GPAs (Weighted)]")
    gpas = calculate_weighted_gpa()
    print(gpas)

    print("\n[Student Rankings (Percentiles)]")
    ratings = calculate_student_ratings()
    print(ratings)

    print("\n[Module Improvement Analysis]")
    improvement = get_improvement_projections(student_id)
    print(f"Target Module: {improvement['module']}")
    print(f"Reason: {improvement['reason']}")

    print("\n--- ANALYSIS COMPLETE: Results exported to analysis_results.json ---")

if __name__ == "__main__":
    # In a real project, this ID would come from the Login page
    current_student_id = 1
    run_analysis_pipeline(current_student_id)