class CreateGradeLevels < ActiveRecord::Migration
  def change
    create_table :grade_levels do |t|

      t.timestamps null: false
    end
  end
end
