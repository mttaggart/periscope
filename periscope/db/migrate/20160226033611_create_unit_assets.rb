class CreateUnitAssets < ActiveRecord::Migration
  def change
    create_table :unit_assets do |t|

      t.timestamps null: false
    end
  end
end
