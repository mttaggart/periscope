class CreateGradeLevels < ActiveRecord::Migration
  def change
    create_table :grade_levels do |t|
      t.string :name
      t.integer :rank
      t.timestamps null: false
      t.references :school, foreign_key: true
    end
  end
end
