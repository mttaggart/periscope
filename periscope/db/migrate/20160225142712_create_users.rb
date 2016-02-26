class CreateUsers < ActiveRecord::Migration
  def change
    create_table :users do |t|
      t.text :email
      t.text :password_digest
      t.timestamps null: false
      t.references :school, foreign_key: true
    end
  end
end
