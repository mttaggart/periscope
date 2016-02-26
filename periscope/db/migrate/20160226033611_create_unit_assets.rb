class CreateUnitAssets < ActiveRecord::Migration
  def change
    create_table :unit_assets do |t|
      t.string :text
      t.integer :rank
      t.references :unit_class, foreign_key: true
      t.timestamps null: false
    end
  end
end
