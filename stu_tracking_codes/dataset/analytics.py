import pandas as pd
import json
import os  # Required for file path management
from database import load_v2_data


def calculate_weighted_gpa():
    # Ensures we find subjects.csv even if main.py is run from a different folder
    base_dir = os.path.dirname(os.path.abspath(__file__))
    subjects_path = os.path.join(base_dir, "subjects.csv")

    # Safety: Create subjects.csv if missing (Matching your SQL Schema)
    if not os.path.exists(subjects_path):
        pd.DataFrame({
            "subject_id": [1, 2, 3, 4, 5],
            "name": ["Advanced Math", "Database Systems", "Web Dev", "Academic English", "Data Structures"],
            "credits": [5, 4, 4, 3, 5]
        }).to_csv(subjects_path, index=False)

    students_df, grades_df = load_v2_data()
    subjects_df = pd.read_csv(subjects_path)

    # TASK: Join Tables (SQL JOIN equivalent in Pandas)
    df = pd.merge(grades_df, subjects_df, on="subject_id")

    # ALGORITHM: Weighted GPA
    df["weighted_points"] = df["points"] * df["credits"]
    grouped = df.groupby("student_id")
    gpa_series = (grouped["weighted_points"].sum() / grouped["credits"].sum()).round(2)

    return gpa_series


def calculate_student_ratings():
    gpas = calculate_weighted_gpa()
    # TASK: Rating among students (Percentile Rank)
    ratings = gpas.rank(pct=True).apply(lambda x: f"Top {round((1 - x) * 100)}%")
    return ratings


def get_improvement_logic(student_id):
    _, grades_df = load_v2_data()
    student_data = grades_df[grades_df['student_id'] == student_id]

    if student_data.empty:
        return "No data available"

    # TASK: What module to improve (Algorithm finds lowest percentage)
    worst_idx = student_data['percentage'].idxmin()
    module_id = student_data.loc[worst_idx, 'subject_id']
    score = student_data.loc[worst_idx, 'percentage']

    return f"Subject ID {module_id} (Current: {score}%). Priority: High."


def export_analyst_results(student_id):
    # This gathers all your Analyst Tasks into one JSON for the Web
    gpas = calculate_weighted_gpa()
    ratings = calculate_student_ratings()

    results = {
        "gpa": float(gpas.get(student_id, 0.0)),
        "rating": ratings.get(student_id, "N/A"),
        "improvement_target": get_improvement_logic(student_id),
        "status": "Analysis Complete"
    }

    # Save to JSON for records.html to use
    with open('analysis_results.json', 'w') as f:
        json.dump(results, f, indent=4)
    print(f"--- Analysis Exported for Student {student_id} ---")


if __name__ == "__main__":
    export_analyst_results(1)