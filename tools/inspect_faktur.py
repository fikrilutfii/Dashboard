import pandas as pd
import json

file_path = 'FAKTURR.xlsx'
sheets = ['FAKTUR TOKO ANDI', 'FAKTUR UMUM 2']

for sheet in sheets:
    print(f"--- Sheet: {sheet} ---")
    try:
        df = pd.read_excel(file_path, sheet_name=sheet, nrows=5)
        print(df.to_json(orient='records'))
    except Exception as e:
        print(f"Error reading {sheet}: {e}")
