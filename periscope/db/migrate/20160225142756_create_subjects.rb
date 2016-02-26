class CreateSubjects < ActiveRecord::Migration
  def change
    create_table :subjects do |t|
      t.string :long_name
      t.string :short_name
      t.references :school, foreign_key: true
      t.timestamps null: false
    end
  end
end
