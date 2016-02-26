class CreateUnits < ActiveRecord::Migration
  def change
    create_table :units do |t|
      t.string :name
      t.text :comments
      t.references :user, foreign_key: true
      t.references :grade_levels
      t.references :subject
      t.date :start_date
      t.date :end_date
      t.timestamps null: false
    end
  end
end
