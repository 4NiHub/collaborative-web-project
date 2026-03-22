import pandas as pd

students_file = "students.csv"

def load_v2_data():
    # Load your updated CSVs that match school_db.sql
    students_df = pd.read_csv('students.csv') # columns: student_id, user_id, name, rating
    grades_df = pd.read_csv('grades.csv')     # columns: student_id, subject_id, grade, points
    return students_df, grades_df

def load_students():
    return pd.read_csv(students_file)

def add_student(student_id, name, major):
    df = load_students()
    new_student = pd.DataFrame([[student_id, name, major]],
                               columns=["student_id","name","major"])
    df = pd.concat([df, new_student], ignore_index=True)
    df.to_csv(students_file, index=False)
    print("Student added!")

def delete_student(student_id):
    df = load_students()
    df = df[df.student_id != student_id]
    df.to_csv(students_file, index=False)
    print("Student deleted!")

def show_students():
    df = load_students()
    print(df)